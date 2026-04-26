<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'reference', 'description', 'price',
        'stock', 'image', 'gallery', 'category_id', 'is_featured',
    ];

    protected $casts = [
        'gallery'     => 'array',
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . uniqid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFeatured($query)    { return $query->where('is_featured', true); }
    public function scopeInStock($query)     { return $query->where('stock', '>', 0); }
}