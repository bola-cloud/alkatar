<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class SmartLifeErpService
{
    protected $apiUrl;
    protected $credentials;
    protected $customerId;
    protected $tokenCacheTtl;
    protected $paymentAccountId;

    public function __construct()
    {
        $this->apiUrl = config('smartlife.api_url');
        $this->credentials = config('smartlife.credentials');
        $this->customerId = config('smartlife.customer_id');
        $this->tokenCacheTtl = config('smartlife.token_cache_ttl');
        $this->paymentAccountId = config('smartlife.payment_account_id', '1020100001');
    }

    /**
     * Check if an HTTP response is HTML (indicates session timeout/login redirect)
     */
    protected function isHtmlResponse($response)
    {
        if (!$response) return false;
        $contentType = $response->header('Content-Type') ?? '';
        $body = $response->body() ?? '';
        return strpos($contentType, 'text/html') !== false || strpos($body, '<!DOCTYPE html>') !== false;
    }

    /**
     * Login and return BOTH the access token AND session cookies.
     * The SmartLife ERP uses cookie/session-based auth — the token alone is not enough.
     * We must capture Set-Cookie headers from the login response and forward them.
     */
    protected function loginWithCookies()
    {
        try {
            // Use Guzzle directly to capture cookies
            $jar = new \GuzzleHttp\Cookie\CookieJar();

            $client = new \GuzzleHttp\Client([
                'cookies' => $jar,
                'verify' => false,
                'timeout' => 15,
            ]);

            $guzzleResponse = $client->post("{$this->apiUrl}/user/login", [
                'form_params' => $this->credentials,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $body = (string) $guzzleResponse->getBody();
            $data = json_decode($body, true);

            if (isset($data['success']) && $data['success'] === true && isset($data['access_token'])) {
                // Extract cookies as an associative array
                $cookies = [];
                foreach ($jar->toArray() as $cookie) {
                    $cookies[$cookie['Name']] = $cookie['Value'];
                }

                Log::info('SmartLife ERP login successful (with cookies)', [
                    'user_id' => $data['user_id'] ?? null,
                    'cookie_count' => count($cookies),
                    'cookie_names' => array_keys($cookies),
                ]);

                return [
                    'token' => $data['access_token'],
                    'cookies' => $cookies,
                ];
            }

            Log::error('SmartLife ERP login failed', ['response' => $data]);
            return null;

        } catch (Exception $e) {
            Log::error('SmartLife ERP login exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get cached session (token + cookies). If not cached, fetch atomically.
     */
    protected function getSession()
    {
        $session = Cache::get('smartlife_session');

        if (!$session) {
            return $this->refreshSessionAtomically();
        }

        return $session;
    }

    /**
     * Refresh the session atomically (token + cookies) using a cache lock
     * to prevent concurrent logins from thrashing the single ERP session.
     */
    protected function refreshSessionAtomically()
    {
        // Mutex for the LOGIN process
        $lock = Cache::lock('smartlife_token_refresh', 20);

        try {
            if ($lock->block(10)) {
                // Double check if another process already populated the cache while we waited
                $session = Cache::get('smartlife_session');
                if ($session) {
                    return $session;
                }

                $session = $this->loginWithCookies();
                if ($session) {
                    Cache::put('smartlife_session', $session, $this->tokenCacheTtl);
                }
                return $session;
            }

            Log::error('SmartLife ERP: Failed to acquire session refresh lock');
            return null;

        } catch (\Exception $e) {
            Log::error('SmartLife ERP: Session refresh lock error', ['error' => $e->getMessage()]);
            return $this->loginWithCookies();
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * High-Level Synchronized Request Handler.
     * This uses a GLOBAL MUTEX for every ERP call.
     * Why? The ERP enforces a single active session. Concurrent requests (sync vs checkout)
     * invalidate each other. Serializing them ensures session stability.
     */
    protected function request($method, $endpoint, $data = [], $headers = [])
    {
        // GLOBAL MUTEX: Only ONE process talks to the SmartLife ERP at a time across the whole system.
        // This is THE solution to prevent session thrashing by the background sync.
        $globalLock = Cache::lock('smartlife_global_request_lock', 30);

        try {
            if ($globalLock->block(20)) {
                return $this->executeSynchronizedRequest($method, $endpoint, $data, $headers);
            }

            Log::error("SmartLife ERP: Global request timeout for {$endpoint}. ERP might be slow or overloaded.");
            return null;
        } catch (\Exception $e) {
            Log::error("SmartLife ERP global lock error for {$endpoint}", ['error' => $e->getMessage()]);
            return null;
        } finally {
            optional($globalLock)->release();
        }
    }

    /**
     * The internal request logic, executed under the global mutex.
     */
    protected function executeSynchronizedRequest($method, $endpoint, $data = [], $headers = [])
    {
        $session = $this->getSession();
        if (!$session || empty($session['token'])) {
            Log::error("SmartLife ERP: No session available (after login attempt).");
            return null;
        }

        $url = Str::startsWith($endpoint, 'http') ? $endpoint : "{$this->apiUrl}/{$endpoint}";

        $makeRequest = function($sess) use ($method, $url, $data, $headers, $endpoint) {
            $cookieString = collect($sess['cookies'] ?? [])
                ->map(fn($v, $k) => "$k=$v")
                ->join('; ');

            $request = Http::withHeaders(array_merge([
                'Authorization' => $sess['token'],
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => $this->apiUrl,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0.4472.124 Safari/537.36',
                'Cookie' => $cookieString,
            ], $headers));

            Log::debug("SmartLife ERP Request Details", [
                'endpoint' => $endpoint,
                'method' => $method,
                'has_cookie' => !empty($cookieString)
            ]);

            return $method === 'GET' ? $request->get($url, $data) : $request->post($url, $data);
        };

        $response = $makeRequest($session);

        // If we get HTML or an auth error, we refresh and retry ONCE
        if ($this->isHtmlResponse($response) || $response->status() === 401) {
            $bodyPreview = substr($response->body(), 0, 1000);
            Log::warning("SmartLife ERP: Auth Failure/HTML Response for $endpoint. Body Preview: $bodyPreview. Triggering atomic refresh...");

            Cache::forget('smartlife_session');
            $newSession = $this->refreshSessionAtomically();

            if ($newSession) {
                // Retry with new session
                $response = $makeRequest($newSession);

                if ($this->isHtmlResponse($response)) {
                    $bodyPreviewAfter = substr($response->body(), 0, 1000);
                    Log::error("SmartLife ERP: Persistent HTML response after refresh for $endpoint", [
                        'status' => $response->status(),
                        'body_preview' => $bodyPreviewAfter
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * Get access token (cached) — legacy compatibility
     */
    public function getAccessToken()
    {
        $session = $this->getSession();
        return $session['token'] ?? null;
    }

    /**
     * Login to SmartLife ERP — legacy compatibility wrapper
     */
    protected function login()
    {
        $result = $this->loginWithCookies();
        return $result['token'] ?? null;
    }

    /**
     * Get products list from SmartLife ERP
     *
     * @param int $offset
     * @param int $limit
     * @return array|null
     */
    public function getProducts($offset = 0, $limit = 100)
    {
        try {
            // v3 API uses get_products_list
            $response = $this->request('GET', 'products/get_products_list', [
                'offset' => $offset,
                'limit' => $limit
            ]);

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    return $data;
                }
                Log::error('SmartLife ERP get products failed', ['response' => $data]);
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP get products exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get categories list from SmartLife ERP
     *
     * @return array|null
     */
    public function getCategories()
    {
        try {
            // v3/v2 Path Exploration:
            // 1. Try category/categories_list (matches branch/branches_list pattern)
            $response = $this->request('GET', 'category/categories_list', ['type' => 'product']);

            if (!$response || !$response->successful()) {
                // 2. Try categories/get_categories_list
                $response = $this->request('GET', 'categories/get_categories_list', ['type' => 'product']);
            }

            if (!$response || !$response->successful()) {
                // 3. Fallback to original taxonomy GET
                $response = $this->request('GET', 'taxonomy', ['type' => 'product']);
            }

            if ($response && $response->successful()) {
                return $response->json();
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP get categories exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get all products (handles pagination automatically)
     *
     * @return array
     */
    /**
     * Get product details from SmartLife ERP
     *
     * @param int $id
     * @return array|null
     */
    public function getProductDetails($id)
    {
        try {
            // v2/v3 pattern: GET with path parameter /products/product/{id}
            $response = $this->request('GET', "products/product/{$id}");

            if ($response && $response->successful()) {
                $data = $response->json();
                $product = $data['data'] ?? null;

                // SPECIAL CASE: If product is combo but NO combo_items found, try v2 specifically
                if ($product && isset($product['type']) && strtolower($product['type']) == 'combo' && !isset($product['combo_items'])) {
                    Log::debug("SmartLife ERP: Combo detected but no items in v3, trying v2 fallback for ID {$id}");
                    $v2Url = str_replace('/v3', '/v2', $this->apiUrl) . "/products/product/{$id}";
                    $v2Response = $this->request('GET', $v2Url);
                    if ($v2Response && $v2Response->successful()) {
                        $v2Data = $v2Response->json();
                        if (isset($v2Data['data']['combo_items'])) {
                            $product['combo_items'] = $v2Data['data']['combo_items'];
                            Log::info("SmartLife ERP: Successfully fetched combo items from v2 fallback for ID {$id}");
                        }
                    }
                }

                if (isset($data['success']) && $data['success'] === true) {
                    return $product;
                }
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP get product details exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function getAllProducts($limit = 100)
    {
        $allProducts = [];
        $offset = 0;

        do {
            $result = $this->getProducts($offset, $limit);

            if (!$result || !isset($result['data'])) {
                break;
            }

            $products = $result['data'];
            $allProducts = array_merge($allProducts, $products);

            $totalCount = (int) ($result['total_count'] ?? 0);
            $offset += $limit;

            // Continue while we haven't fetched all products
        } while (count($allProducts) < $totalCount);

        return $allProducts;
    }

    /**
     * Create customer in SmartLife ERP
     *
     * @param string $name Customer name
     * @param string $phone Customer phone number
     * @param int $customerGroupId Customer group ID (default: 6)
     * @return array|null Returns ['success' => true, 'id' => customer_id, 'message' => ...] on success
     */
    public function createCustomer($name, $phone, $customerGroupId = 6)
    {
        try {
            $payload = [
                'name' => $name,
                'phone' => $phone,
                'customer_group_id' => $customerGroupId
            ];

            $response = $this->request('POST', 'clients/add', $payload);

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    return $data;
                }
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP create customer exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Update customer in SmartLife ERP
     *
     * @param string $id
     * @param string $name
     * @param string $phone
     * @return array|null
     */
    public function updateCustomer($id, $name, $phone)
    {
        try {
            $payload = [
                'name' => $name,
                'phone' => $phone
            ];

            $response = $this->request('POST', "clients/update/$id", $payload);

            if ($response && $response->successful()) {
                return $response->json();
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP update customer exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Add sale to SmartLife ERP
     *
     * @param array $products Array of products with id, barcode, name, price, quantity
     * @param string|null $customerId Optional customer ID (defaults to config value)
     * @param array $saleDetails Optional sale details including discounts, order reference, etc.
     * @return array|null
     */
    public function addSale(array $products, $customerId = null, array $saleDetails = [])
    {
        try {
            if (empty($products)) {
                Log::error('SmartLife ERP: Cannot add sale - no products provided');
                return null;
            }

            $payload = [
                'customer_id' => $customerId ?? $this->customerId,
                'products' => $products
            ];

            if (!empty($saleDetails)) {
                if (isset($saleDetails['invoice_no'])) $payload['invoice_no'] = $saleDetails['invoice_no'];
                if (isset($saleDetails['ref_no'])) $payload['ref_no'] = $saleDetails['ref_no'];
                if (isset($saleDetails['order_reference'])) $payload['order_reference'] = $saleDetails['order_reference'];
                if (isset($saleDetails['discount_amount'])) $payload['discount_amount'] = $saleDetails['discount_amount'];
                if (isset($saleDetails['discount_type'])) $payload['discount_type'] = $saleDetails['discount_type'];
                if (isset($saleDetails['status'])) $payload['status'] = $saleDetails['status'];
                if (isset($saleDetails['payment_status'])) $payload['payment_status'] = $saleDetails['payment_status'];
                if (isset($saleDetails['payments'])) $payload['payments'] = $saleDetails['payments'];
                if (isset($saleDetails['notes'])) $payload['notes'] = $payload['additional_notes'] = $saleDetails['notes'];
                if (isset($saleDetails['warehouse_id'])) $payload['warehouse_id'] = $saleDetails['warehouse_id'];
            }

            if (isset($saleDetails['smartlife_invoice_id']) && !empty($saleDetails['smartlife_invoice_id'])) {
                $saleId = $saleDetails['smartlife_invoice_id'];
                $payload['sale_id'] = (string) $saleId;
                
                // v3 UPDATE: Use 'sales/update/{id}' (POST) instead of 'sales/{id}' (PUT)
                // Note: v3 documentation requires the full 'products' list during update.
                $response = $this->request('POST', "sales/update/{$saleId}", $payload);
            } else {
                $response = $this->request('POST', "sales/add", $payload);
            }

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('SmartLife ERP sale successful', ['sale_id' => $data['data'] ?? $data['id'] ?? null]);
                    return $data;
                }
                Log::error('SmartLife ERP addSale: API returned success=false', [
                    'response' => $data,
                ]);
            } else {
                Log::error('SmartLife ERP addSale: Request failed', [
                    'status' => $response ? $response->status() : 'null',
                    'body' => $response ? substr($response->body(), 0, 500) : 'null',
                ]);
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP add sale exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Add payment to an existing sale in SmartLife ERP
     *
     * @param int|string $saleId
     * @param float $amount
     * @param string $paidBy
     * @param string $note
     * @param string $chequeNo
     * @return array|null
     */
    public function addPayment($saleId, $amount, $paidBy = 'cash', $note = '', $chequeNo = '')
    {
        try {
            // v3 API endpoint is sales/add_payments/{id} (plural)
            // Payload MUST be a specifically formatted JSON array under 'payments'
            $payload = [
                'date' => now()->toDateTimeString(),
                'payments' => [
                    [
                        'id' => in_array(strtolower($paidBy), ['card', 'thawani', 'online']) ? '4' : '1', // 4 is Card, 1 is Cash
                        'amount' => (string) $amount,
                        'note' => $note,
                        'cheque_no' => $chequeNo
                    ]
                ]
            ];

            Log::info('SmartLife ERP addPayments request (v3)', ['sale_id' => $saleId, 'amount' => $amount]);

            $response = $this->request('POST', "sales/add_payments/{$saleId}", $payload);

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('SmartLife ERP payment added successfully', ['sale_id' => $saleId, 'response' => $data]);
                    return $data;
                }
                Log::error('SmartLife ERP addPayment: API returned success=false', [
                    'sale_id' => $saleId,
                    'response' => $data,
                ]);
            } else {
                Log::error('SmartLife ERP addPayment: Request failed', [
                    'sale_id' => $saleId,
                    'status' => $response ? $response->status() : 'null',
                    'body' => $response ? substr($response->body(), 0, 500) : 'null',
                ]);
            }
            return null;
        } catch (Exception $e) {
            Log::error('SmartLife ERP addPayment exception', ['sale_id' => $saleId, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Clear cached token (useful if token expires or becomes invalid)
     *
     * @return void
     */
    public function clearTokenCache()
    {
        Cache::forget('smartlife_access_token');
        Cache::forget('smartlife_session');
    }

    /**
     * Test connection to SmartLife ERP
     *
     * @return bool
     */
    public function testConnection()
    {
        $token = $this->getAccessToken();
        return !is_null($token);
    }
    /**
     * Submit an Order to SmartLife ERP (Sync)
     * This prepares the product list and calls addSale
     *
     * @param \App\Models\Admin\Order $order
     * @return string|null Invoice ID or null
     */
    public function submitOrder($order)
    {
        try {
            $products = [];
            $customerId = $order->user ? $order->user->smartlife_customer_id : null;

            $billingAddressRaw = $order->billing_address;
            $billingAddress = is_string($billingAddressRaw) ? json_decode($billingAddressRaw, true) : $billingAddressRaw;

            $name = (is_array($billingAddress) ? ($billingAddress['name'] ?? null) : null) ?? ($order->user->name ?? 'Customer');
            $phone = (is_array($billingAddress) ? ($billingAddress['phone_number'] ?? null) : null)
                ?? ($order->user->Number ?? $order->billing_phone ?? '0000');

            // If phone is invalid/null, use a safer fallback
            if (empty($phone) || $phone == 'null') {
                $phone = '0000';
            }

            // If customer ID is missing, try to create one
            if (!$customerId) {
                $cust = $this->createCustomer($name, $phone);
                if ($cust && isset($cust['id'])) {
                    $customerId = $cust['id'];
                    if ($order->user) {
                        $order->user->smartlife_customer_id = $customerId;
                        $order->user->save();
                    }
                }
            } else {
                // Customer exists - UPDATE them to ensure phone number is synchronized
                // This fixes the "dummy number" issue for existing customers
                $this->updateCustomer($customerId, $name, $phone);
            }

            foreach ($order->order_details as $detail) {
                $product = $detail->product;
                $smartLifeId = null;
                $barcode = null;

                if ($product) {
                    if ($product->smartlife_id) {
                        $smartLifeId = $product->smartlife_id;
                    } elseif ($product->barcode) {
                        $barcode = $product->barcode;
                        // Check shadow table
                        $shadow = \App\Models\SmartLifeProduct::where('barcode', $barcode)->first();
                        if ($shadow) {
                            $smartLifeId = $shadow->smartlife_id;
                        }
                    }

                    // Fallback: Try name matching in shadow table if ID/Barcode failed
                    if (!$smartLifeId) {
                        $smartLifeProduct = \App\Models\SmartLifeProduct::where('name', 'LIKE', '%' . $detail->Product_Name . '%')->first();
                        if ($smartLifeProduct) {
                            $smartLifeId = $smartLifeProduct->smartlife_id;
                            $barcode = $smartLifeProduct->barcode ?? $barcode; // Use shadow barcode if original missing
                        }
                    }
                }

                if ($smartLifeId) {
                    $products[] = [
                        'id' => $smartLifeId,
                        'barcode' => $barcode ?? '0000',
                        'name' => $detail->Product_Name,
                        'price' => (float) $detail->Price,
                        'quantity' => (int) $detail->Quantity,
                    ];
                }
            }

            if (empty($products)) {
                return null;
            }

            $status = 'final'; // Use 'final' for completed sales
            $paymentStatus = 'due';
            $payments = [];

            Log::info('SmartLife Payment Status Check', [
                'is_paid' => $order->is_paid,
                'Payment_Status' => $order->Payment_Status,
                'Payment_Method' => $order->Payment_Method,
                'order_number' => $order->Order_Number
            ]);

            // detailed check for payment status
            $isPaid = $order->is_paid ||
                in_array($order->Payment_Status, ['Paid', 'Success', 'SUCCESS', 'payment_success', 'Successful']) ||
                (defined('PAYMENT_SUCCESS') && $order->Payment_Status == PAYMENT_SUCCESS);

            if ($isPaid) {
                $paymentStatus = 'Paid'; // Use capitalized 'Paid' for better ERP support

                // Use the exact payment format provided by the SmartLife developer
                // id 4 is typically for bank/card/thawani payment in their system
                $payments[] = [
                    'id' => '4',
                    'amount' => (string) $order->Grand_Total,
                    'balance' => '0'
                ];
            }

            $saleDetails = [
                'invoice_no' => $order->Order_Number,
                'ref_no' => $order->Order_Number,
                'order_reference' => $order->Order_Number,
                'notes' => 'Order ' . $order->Order_Number,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'smartlife_invoice_id' => $order->smartlife_invoice_id,
            ];

            // If the order is already synced to ERP and is now PAID, use addPayment specifically
            if ($order->smartlife_invoice_id && $isPaid) {
                Log::info("SmartLife Sync: Order {$order->Order_Number} already has invoice #{$order->smartlife_invoice_id}. Adding payment directly.");
                $paymentResult = $this->addPayment(
                    $order->smartlife_invoice_id,
                    $order->Grand_Total,
                    'Card', // or use $order->Payment_Method
                    'Thawani Payment Successful'
                );
                
                if ($paymentResult && isset($paymentResult['success']) && $paymentResult['success']) {
                    Log::info("SmartLife Sync: Payment successfully added to invoice #{$order->smartlife_invoice_id}.");
                    // If this was just a payment update, we might be done.
                    // But let's let addSale run to update status to 'Paid'/'final' if possible.
                } else {
                    Log::warning("SmartLife Sync: Payment failed for invoice #{$order->smartlife_invoice_id}. Attempting redundant addSale update.");
                }
            }

            // Add payments array using plural key as requested
            if (!empty($payments)) {
                $saleDetails['payments'] = $payments;
            }

            // Add total discount amount if available
            $totalDiscount = 0;
            if ($order->Coupon_Amount > 0) {
                $totalDiscount += $order->Coupon_Amount;
                if ($order->coupon) {
                    $saleDetails['coupon_code'] = $order->coupon->CouponCode ?? null;
                }
            }

            // Subscription discount (from session or order if saved?)
            // Assuming order has total discount saved in Coupon_Amount or similar
            // If subscription discount is separate, we might need to fetch it.
            // For now, use Coupon_Amount as the main discount.

            if ($totalDiscount > 0) {
                $saleDetails['discount_amount'] = $totalDiscount;
                $saleDetails['discount_type'] = 'fixed';
            }

            $result = $this->addSale($products, $customerId, $saleDetails);

            if ($result && isset($result['success']) && $result['success']) {
                // The sale ID is usually in $result['data'] or $result['id']
                return $result['data'] ?? $result['id'] ?? '1';
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SmartLife submitOrder failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
