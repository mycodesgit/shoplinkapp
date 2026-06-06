<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'product_variations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'variant_product_id',
        'variation_name',
        'variation_value',
        'variant_sku',
        'variant_price',
        'variant_stock',
        'variant_image',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'variant_price' => 'decimal:2',
        'variant_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'variant_product_id');
    }

    /**
     * Check if variation is in stock
     */
    public function inStock()
    {
        return $this->variant_stock > 0;
    }

    /**
     * Get full variation name (e.g., "Color: Black")
     */
    public function getFullVariationAttribute()
    {
        return $this->variation_name . ': ' . $this->variation_value;
    }

    /**
     * Decrease stock when purchased
     */
    public function decreaseStock($quantity)
    {
        if ($this->variant_stock >= $quantity) {
            $this->variant_stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Increase stock when restocking
     */
    public function increaseStock($quantity)
    {
        $this->variant_stock += $quantity;
        $this->save();
        return $this->variant_stock;
    }

    /**
     * Scope a query to get variations by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('variation_name', $type);
    }

    /**
     * Scope a query to get in-stock variations only
     */
    public function scopeInStock($query)
    {
        return $query->where('variant_stock', '>', 0);
    }

    /**
     * Get the formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->variant_price, 2);
    }

    /**
     * Get product price with variation override
     */
    public function getEffectivePriceAttribute()
    {
        return $this->variant_price ?? $this->product->prdctprice;
    }
}
