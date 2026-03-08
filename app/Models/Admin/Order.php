<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'Order_Number',
        'User_Id',
        'admin_id',
        'Billing_Id',
        'Shipping_Id',
        'billing_address',
        'shipping_address',
        'Coupon_Id',
        'Coupon_Code',
        'Coupon_Amount',
        'Delivery_Charge',
        'Sub_Total',
        'Tax',
        'Grand_Total',
        'Is_Free_Delivery',
        'Is_Order_Successful',
        'Is_Order_Completed',
        'Payment_Method',
        'Payment_Status',
        'Order_Status',
        'Delivery_At',
        'txn',
        'order_source',
        // Webhook payment support fields
        'is_paid',
        'pending_token',
        'payment_session_id',
        'smartlife_invoice_id',
        'smartlife_synced_at',
        'is_printed',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'Is_Order_Successful' => 'boolean',
        'Is_Order_Completed' => 'boolean',
        'Is_Free_Delivery' => 'boolean',
    ];

    public function order_details()
    {
        return $this->hasMany(OrderDetails::class, 'Order_Id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_Id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'Coupon_Id');
    }

    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'Billing_Id');
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'Shipping_Id');
    }

    public function getStatusLang()
    {
        return [
            ORDER_PENDING => [
                'status_en' => 'Pending',
                'status_ar' => 'معلق',
            ],
            ORDER_PROCESSING => [
                'status_en' => 'Processing',
                'status_ar' => 'قيد المعالجة',
            ],
            ORDER_SHIPPED => [
                'status_en' => 'Shipped',
                'status_ar' => 'تم الشحن',
            ],
            ORDER_DELIVERED => [
                'status_en' => 'Delivered',
                'status_ar' => 'تم التوصيل',
            ],
            ORDER_CANCELLED => [
                'status_en' => 'Cancelled',
                'status_ar' => 'ملغي',
            ],
            ORDER_RETURN => [
                'status_en' => 'Returned',
                'status_ar' => 'مرجع',
            ],
            ORDER_NOT_PAYMENT_YET => [
                'status_en' => 'Not Paid Yet',
                'status_ar' => 'لم يُدفع بعد',
            ],
            ORDER_DELIVERED_FAILED => [
                'status_en' => 'Delivery Failed',
                'status_ar' => 'فشل التسليم',
            ],
        ];
    }

    /**
     * Normalize phone number by removing non-digits and leading zeros/country codes
     * for comparison purposes.
     */
    public static function normalizePhone($phone)
    {
        if (!$phone) return '';
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // Take last 8 digits (standard length for numbers in Oman, excluding prefixes)
        return substr($phone, -8);
    }

    /**
     * Get clean billing address array, handling double-encoding.
     */
    public function getBillingAddressAttribute($value)
    {
        $decoded = json_decode($value, true);
        if (is_string($decoded)) {
            $inner = json_decode($decoded, true);
            return is_array($inner) ? $inner : $decoded;
        }
        return $decoded;
    }

    /**
     * Get clean shipping address array, handling double-encoding.
     */
    public function getShippingAddressAttribute($value)
    {
        $decoded = json_decode($value, true);
        if (is_string($decoded)) {
            $inner = json_decode($decoded, true);
            return is_array($inner) ? $inner : $decoded;
        }
        return $decoded;
    }

    /**
     * Helper for backward compatibility or explicit details access.
     */
    public function getBillingAddressDetailsAttribute()
    {
        return $this->billing_address;
    }

    /**
     * Helper for backward compatibility or explicit details access.
     */
    public function getShippingAddressDetailsAttribute()
    {
        return $this->shipping_address;
    }

    /**
     * Get a formatted string of the billing address.
     */
    public function getFormattedBillingAddressAttribute()
    {
        $details = $this->billing_address_details;
        if (!$details || !is_array($details)) {
            return 'N/A';
        }

        return implode(', ', array_filter([
            $details['street'] ?? null,
            $details['area_ar'] ?? $details['area_en'] ?? null,
            $details['city_ar'] ?? $details['city_en'] ?? null,
            $details['state_ar'] ?? $details['state_en'] ?? null,
        ]));
    }

    /**
     * Get a formatted string of the shipping address.
     */
    public function getFormattedShippingAddressAttribute()
    {
        $details = $this->shipping_address_details;
        if (!$details || !is_array($details)) {
            return 'N/A';
        }

        return implode(', ', array_filter([
            $details['street'] ?? null,
            $details['area_ar'] ?? $details['area_en'] ?? null,
            $details['city_ar'] ?? $details['city_en'] ?? null,
            $details['state_ar'] ?? $details['state_en'] ?? null,
        ]));
    }
}
