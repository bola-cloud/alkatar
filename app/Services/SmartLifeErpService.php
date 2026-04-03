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
     * Atomically refresh the access token using a cache lock to prevent
     * concurrent logins from thrashing the single ERP session.
     */
    protected function refreshTokenAtomically()
    {
        // Use a cache lock so only ONE process/request logs in at a time
        $lock = Cache::lock('smartlife_token_refresh', 10); // 10 second lock

        try {
            // Wait up to 5 seconds to acquire the lock
            if ($lock->block(5)) {
                // Double-check: another request may have already refreshed the token
                // while we were waiting for the lock
                $this->clearTokenCache();
                $newToken = $this->login();
                if ($newToken) {
                    Cache::put('smartlife_access_token', $newToken, $this->tokenCacheTtl);
                }
                return $newToken;
            }

            // Lock not acquired: another process is refreshing. Wait briefly
            // and then read whatever token they stored.
            Log::info('SmartLife ERP: Waiting for concurrent token refresh...');
            usleep(500000); // 0.5 seconds
            return Cache::get('smartlife_access_token');

        } catch (\Exception $e) {
            Log::error('SmartLife ERP: Token refresh lock error', ['error' => $e->getMessage()]);
            // Fallback: just do a plain refresh
            $this->clearTokenCache();
            return $this->getAccessToken();
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * Common request handler with automatic retry on session timeout (HTML response).
     * Uses atomic token refresh to prevent concurrent logins from invalidating each other.
     */
    protected function request($method, $endpoint, $data = [], $headers = [])
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $url = Str::startsWith($endpoint, 'http') ? $endpoint : "{$this->apiUrl}/{$endpoint}";

        $makeRequest = function($currentToken) use ($method, $url, $data, $headers) {
            $request = Http::withHeaders(array_merge([
                'Authorization' => $currentToken,
                'Accept' => 'application/json',
            ], $headers));

            return $method === 'GET' ? $request->get($url, $data) : $request->post($url, $data);
        };

        $response = $makeRequest($token);

        if ($this->isHtmlResponse($response)) {
            Log::warning("SmartLife ERP: HTML response for {$endpoint}. Refreshing token atomically...");

            $newToken = $this->refreshTokenAtomically();
            if ($newToken) {
                $response = $makeRequest($newToken);

                // If STILL HTML after atomic refresh, log the body and give up
                if ($this->isHtmlResponse($response)) {
                    Log::error("SmartLife ERP: Still HTML after token refresh for {$endpoint}", [
                        'status' => $response->status(),
                        'body_preview' => substr($response->body(), 0, 500),
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * Get access token (cached)
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return Cache::remember('smartlife_access_token', $this->tokenCacheTtl, function () {
            return $this->login();
        });
    }

    /**
     * Login to SmartLife ERP and get access token
     *
     * @return string|null
     */
    protected function login()
    {
        try {
            $response = Http::post("{$this->apiUrl}/user/login", $this->credentials);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true && isset($data['access_token'])) {
                    Log::info('SmartLife ERP login successful', ['user_id' => $data['user_id'] ?? null]);
                    return $data['access_token'];
                }

                Log::error('SmartLife ERP login failed', ['response' => $data]);
                return null;
            }

            Log::error('SmartLife ERP login request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (Exception $e) {
            Log::error('SmartLife ERP login exception', ['error' => $e->getMessage()]);
            return null;
        }
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
            $response = $this->request('GET', 'taxonomy', ['type' => 'product']);

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
            $response = $this->request('GET', 'products/get_product_details', ['id' => $id]);

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    return $data['data'] ?? null;
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
                $payload['sale_id'] = $saleId;
                $payload['_method'] = 'PUT';
                $response = $this->request('POST', "sales/{$saleId}", $payload);
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
            $payload = [
                'sale_id' => (string) $saleId,
                'sell_id' => (string) $saleId,
                'transaction_id' => (string) $saleId,
                'amount' => (float) $amount,
                'account_id' => $this->paymentAccountId,
                'paid_by' => in_array(strtolower($paidBy), ['card', 'thawani', 'online']) ? 'Card' : 'Cash',
                'method' => in_array(strtolower($paidBy), ['card', 'thawani', 'online']) ? 'card' : 'cash',
                'payment_status' => 'Paid',
                'note' => $note,
                'cheque_no' => $chequeNo,
                'paid_on' => now()->toDateTimeString(),
            ];

            Log::info('SmartLife ERP addPayment request', ['sale_id' => $saleId, 'amount' => $amount, 'paid_by' => $paidBy]);

            $response = $this->request('POST', 'sales/add_payment', $payload);

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
