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
}
