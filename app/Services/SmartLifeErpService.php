<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('SmartLife ERP: Cannot get products - no access token');
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get("{$this->apiUrl}/products/get_products_list", [
                        'offset' => $offset,
                        'limit' => $limit
                    ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true) {
                    // Log::info('SmartLife ERP products fetched', [
                    //     'total_count' => $data['total_count'] ?? 0,
                    //     'fetched' => count($data['data'] ?? [])
                    // ]);
                    return $data;
                }

                Log::error('SmartLife ERP get products failed', ['response' => $data]);
                return null;
            }

            Log::error('SmartLife ERP get products request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
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
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('SmartLife ERP: Cannot get categories - no access token');
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get("{$this->apiUrl}/taxonomy", [
                'type' => 'product'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Log::info('SmartLife ERP categories response', ['data' => $data]);
                return $data; 
            }

            Log::error('SmartLife ERP get categories request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
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
            $token = $this->getAccessToken();
            if (!$token)
                return null;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get("{$this->apiUrl}/products/get_product_details", [
                        'id' => $id
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    return $data['data'] ?? null;
                }
                Log::warning('SmartLife ERP details success but logical failure', ['id' => $id, 'data' => $data]);
            } else {
                Log::error('SmartLife ERP product details request failed', [
                    'id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
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
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('SmartLife ERP: Cannot create customer - no access token');
                return null;
            }

            $payload = [
                'name' => $name,
                'phone' => $phone,
                'customer_group_id' => $customerGroupId
            ];

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->apiUrl}/clients/add", $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('SmartLife ERP customer created successfully', [
                        'customer_id' => $data['id'] ?? null,
                        'name' => $name,
                        'phone' => $phone
                    ]);
                    return $data;
                }

                Log::error('SmartLife ERP create customer failed', ['response' => $data]);
                return null;
            }

            Log::error('SmartLife ERP create customer request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
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
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('SmartLife ERP: Cannot update customer - no access token');
                return null;
            }

            $payload = [
                'name' => $name,
                'phone' => $phone
            ];

            // Standard UltimatePOS update endpoint
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->apiUrl}/clients/update/$id", $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('SmartLife ERP customer updated successfully', [
                    'customer_id' => $id,
                    'name' => $name,
                    'phone' => $phone
                ]);
                return $data;
            }

            Log::error('SmartLife ERP update customer request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
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
            $token = $this->getAccessToken();

            if (!$token) {
                Log::error('SmartLife ERP: Cannot add sale - no access token');
                return null;
            }

            if (empty($products)) {
                Log::error('SmartLife ERP: Cannot add sale - no products provided');
                return null;
            }

            $payload = [
                'customer_id' => $customerId ?? $this->customerId,
                'products' => $products
            ];

            // Add optional sale details if provided (discount, order reference, etc.)
            if (!empty($saleDetails)) {
                // Support invoice_no (primary for SmartERP), ref_no, and order_reference for compatibility
                if (isset($saleDetails['invoice_no'])) {
                    $payload['invoice_no'] = $saleDetails['invoice_no'];
                }
                if (isset($saleDetails['ref_no'])) {
                    $payload['ref_no'] = $saleDetails['ref_no'];
                    if (!isset($payload['invoice_no'])) {
                        $payload['invoice_no'] = $saleDetails['ref_no'];
                    }
                }
                if (isset($saleDetails['order_reference'])) {
                    $payload['order_reference'] = $saleDetails['order_reference'];
                    if (!isset($payload['invoice_no'])) {
                        $payload['invoice_no'] = $saleDetails['order_reference'];
                    }
                }

                if (isset($saleDetails['discount_amount'])) {
                    $payload['discount_amount'] = $saleDetails['discount_amount'];
                }
                if (isset($saleDetails['discount_type'])) {
                    $payload['discount_type'] = $saleDetails['discount_type'];
                }
                if (isset($saleDetails['status'])) {
                    $payload['status'] = $saleDetails['status'];
                }
                if (isset($saleDetails['payment_status'])) {
                    $payload['payment_status'] = $saleDetails['payment_status'];
                }

                // Add payment array (plural as requested by developer)
                if (isset($saleDetails['payments'])) {
                    $payload['payments'] = $saleDetails['payments'];
                } else if (isset($saleDetails['payment'])) {
                    // Fallback alias
                    $payload['payments'] = $saleDetails['payment'];
                } else if (isset($saleDetails['paid_by'])) {
                    // Fallback to legacy single payment fields
                    $payload['amount'] = $saleDetails['amount'] ?? 0;
                    $payload['paid_by'] = $saleDetails['paid_by'];
                    $payload['cheque_no'] = $saleDetails['cheque_no'] ?? '';
                }

                if (isset($saleDetails['notes'])) {
                    $payload['notes'] = $saleDetails['notes'];
                    $payload['additional_notes'] = $saleDetails['notes']; // Alias
                }

                if (isset($saleDetails['warehouse_id'])) {
                    $payload['warehouse_id'] = $saleDetails['warehouse_id'];
                }
            }

            Log::info('SmartLife ERP addSale/updateSale Payload', ['payload' => $payload]);

            if (isset($saleDetails['smartlife_invoice_id']) && !empty($saleDetails['smartlife_invoice_id'])) {
                // Update existing invoice (Payment Success Step 2)
                $saleId = $saleDetails['smartlife_invoice_id'];
                $payload['sale_id'] = $saleId; // Important for PUT

                Log::info('SmartLife ERP Updating Existing Invoice to Paid', ['sale_id' => $saleId]);
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->put("{$this->apiUrl}/sales/{$saleId}", $payload);
            } else {
                // Create new invoice (Checkout Step 1)
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post("{$this->apiUrl}/sales/add", $payload);
            }

            // Log full response details
            Log::info('SmartLife ERP API Response', [
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true) {
                    Log::info('SmartLife ERP sale added successfully', [
                        'products_count' => count($products),
                        'response' => $data,
                        'sale_id' => $data['data'] ?? null
                    ]);

                    // Try to fetch the created sale to verify it exists
                    if (isset($data['data'])) {
                        try {
                            $saleId = $data['data'];
                            $verifySale = Http::withHeaders([
                                'Authorization' => $token,
                            ])->get("{$this->apiUrl}/sales/get_sale_details", ['id' => $saleId]);

                            Log::info('Sale verification attempt', [
                                'sale_id' => $saleId,
                                'verification_status' => $verifySale->status(),
                                'verification_body' => $verifySale->body()
                            ]);
                        } catch (\Exception $e) {
                            Log::warning('Could not verify sale creation', ['error' => $e->getMessage()]);
                        }
                    }

                    return $data;
                }

                Log::error('SmartLife ERP add sale failed', ['response' => $data]);
                return null;
            }

            // Check for HTML response (Login page redirect usually indicates invalid token)
            $contentType = $response->header('Content-Type');
            if (strpos($contentType, 'text/html') !== false || strpos($response->body(), '<!DOCTYPE html>') !== false) {
                Log::warning('SmartLife ERP returned HTML (likely login redirect). Clearing token cache.');
                $this->clearTokenCache();
                return null;
            }

            Log::error('SmartLife ERP add sale request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);
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
            $token = $this->getAccessToken();
            if (!$token)
                return null;

            $payload = [
                'sale_id' => $saleId,
                'sell_id' => $saleId, // Alias for some UltimatePOS versions
                'transaction_id' => $saleId, // Alias for some UltimatePOS versions
                'amount' => (float) $amount,
                'paid_by' => $paidBy, // Keep as passed (e.g. 'Cheque' or 'Cash')
                'method' => strtolower($paidBy), // Common alias
                'payment_status' => 'Paid', // Capitalized as it was reportedly working before
                'status' => 'Paid', // Alias
                'note' => $note,
                'cheque_no' => $chequeNo,
                'paid_on' => now()->toDateTimeString(), // Clear timestamp
            ];

            Log::info('SmartLife ERP addPayment Payload', ['payload' => $payload]);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->apiUrl}/sales/add_payment", $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('SmartLife ERP addPayment Response', ['response' => $data]);
                return $data;
            }

            Log::error('SmartLife ERP addPayment request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => "{$this->apiUrl}/sales/add_payment"
            ]);
            return null;

        } catch (Exception $e) {
            Log::error('SmartLife ERP addPayment exception', ['error' => $e->getMessage()]);
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
            $paymentStatus = 'Paid';
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
                $paymentStatus = 'paid';

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
