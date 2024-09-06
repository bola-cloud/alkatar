<?php

namespace App\Enums;

use App\Traits;

enum OrderStatus: int
{

    case ORDER_PENDING = 1;
    case ORDER_PROCESSING = 2;
    case ORDER_SHIPPED = 3;
    case ORDER_DELIVERED = 4;
    case ORDER_CANCELLED = 5;
    case ORDER_RETURN = 6;
    case ORDER_NOT_PAYMENT_YET = 7;
    case ORDER_DELIVERED_FAILED = 8;

    /**
     * Get all order status values as an array
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the translated label for the order status
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ORDER_PENDING => 'pending',
            self::ORDER_PROCESSING => 'processing',
            self::ORDER_SHIPPED => 'shipped',
            self::ORDER_DELIVERED => 'delivered',
            self::ORDER_CANCELLED => 'cancelled',
            self::ORDER_RETURN => 'return',
            self::ORDER_NOT_PAYMENT_YET => 'not_payment_yet',
            self::ORDER_DELIVERED_FAILED => 'delivered_failed',
        };
    }
//    public function label(): string
//    {
//        return match ($this) {
//            self::ORDER_PENDING => __('order_status.pending'),
//            self::ORDER_PROCESSING => __('order_status.processing'),
//            self::ORDER_SHIPPED => __('order_status.shipped'),
//            self::ORDER_DELIVERED => __('order_status.delivered'),
//            self::ORDER_CANCELLED => __('order_status.cancelled'),
//            self::ORDER_RETURN => __('order_status.return'),
//            self::ORDER_NOT_PAYMENT_YET => __('order_status.not_payment_yet'),
//            self::ORDER_DELIVERED_FAILED => __('order_status.delivered_failed'),
//        };
//}
}
