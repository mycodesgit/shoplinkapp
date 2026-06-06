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
}
