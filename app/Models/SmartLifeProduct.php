<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartLifeProduct extends Model
{
    use HasFactory;

    protected $table = 'smartlife_products';

    protected $fillable = [
        'smartlife_id',
        'barcode',
        'name',
        'price',
        'cost',
        'quantity',
        'alert_quantity',
        'type',
        'unit',
        'category',
        'description',
        'thumb',
        'image',
        'show_pos',
        'options',
        'combo_items',
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'cost' => 'decimal:3',
        'quantity' => 'integer',
        'alert_quantity' => 'integer',
        'show_pos' => 'boolean',
        'options' => 'array',
        'combo_items' => 'array',
    ];

    /**
     * Find product by SmartLife ID
     *
     * @param int $smartlifeId
     * @return SmartLifeProduct|null
     */
    public static function findBySmartLifeId($smartlifeId)
    {
        return static::where('smartlife_id', $smartlifeId)->first();
    }

    /**
     * Find product by barcode
     *
     * @param string $barcode
     * @return SmartLifeProduct|null
     */
    public static function findByBarcode($barcode)
    {
        return static::where('barcode', $barcode)->first();
    }

    /**
     * Sync product data from SmartLife ERP response
     *
     * @param array $productData
     * @return SmartLifeProduct
     */
    public static function syncFromSmartLife(array $productData)
    {
        return static::updateOrCreate(
            ['smartlife_id' => $productData['id']],
            [
                'barcode' => $productData['code'] ?? null,
                'name' => $productData['name'],
                'price' => $productData['price'] ?? 0,
                'cost' => $productData['cost'] ?? 0,
                'quantity' => $productData['quantity'] ?? 0,
                'alert_quantity' => $productData['alert_quantity'] ?? 0,
                'type' => $productData['type'] ?? null,
                'unit' => $productData['unit'] ?? null,
                'category' => $productData['category'] ?? null,
                'description' => $productData['description'] ?? null,
                'thumb' => $productData['thumb'] ?? null,
                'image' => $productData['image'] ?? null,
                'show_pos' => $productData['show_pos'] ?? 1,
                'options' => $productData['options'] ?? null,
                'combo_items' => $productData['combo_items'] ?? $productData['combo_products'] ?? null, // Sync combo items (handle variations)
            ]
        );
    }
}
