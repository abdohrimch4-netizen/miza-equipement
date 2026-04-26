<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * علاقة البند بالطلب (اختياري ولكن مفيد)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * علاقة البند بالمنتج (اختياري ولكن مفيد)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}