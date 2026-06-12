<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'products';

    protected $fillable = [
        'catid',
        'subcatid',
        'prdctname',
        'prdctdesc',
        'prdctimage',
        'prdctslug',
        'prdcttag',
        'postedBy',
    ];

    // ADD THIS RELATIONSHIP METHOD
    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'variant_product_id');
    }

    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariation::class,
            'variant_product_id', // Foreign key on product_variations table
            'variation_id', // Foreign key on order_items table
            'id', // Local key on products table
            'id' // Local key on product_variations table
        );
    }

    // Optional: Add relationship to category
    public function category()
    {
        return $this->belongsTo(Category::class, 'catid');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->prdctslug  = Str::slug($product->prdctname);
        });

        static::updating(function ($product) {
            if ($product->isDirty('prdctname')) {
                $product->prdctslug = Str::slug($product->prdctname);
            }
        });
    }

     public function getPriceAttribute()
    {
        return $this->variations->min('variant_price');
    }
    
    // Helper method to get total stock from variations
    public function getStockAttribute()
    {
        return $this->variations->sum('variant_stock');
    }
    
    // Helper method to get all SKUs
    public function getSkusAttribute()
    {
        return $this->variations->pluck('variant_sku');
    }

    // Get available stock (total stock minus ordered quantities)
public function getAvailableStockAttribute()
{
    $totalStock = $this->variations->sum('variant_stock');
    
    $orderedQuantity = OrderItem::whereIn('variation_id', $this->variations->pluck('id'))
        ->whereHas('order', function($query) {
            $query->whereNotIn('status', ['cancelled', 'delivered']);
        })
        ->sum('quantity');
    
    return $totalStock - $orderedQuantity;
}

// Get stock status with warning
public function getStockStatusAttribute()
{
    $available = $this->available_stock;
    
    if ($available <= 0) {
        return 'Out of Stock';
    } elseif ($available <= 10) {
        return "Only {$available} left!";
    } else {
        return "{$available} in stock";
    }
}

// Get stock badge class
public function getStockBadgeClassAttribute()
{
    $available = $this->available_stock;
    
    if ($available <= 0) {
        return 'danger';
    } elseif ($available <= 10) {
        return 'warning';
    } else {
        return 'success';
    }
}
}
