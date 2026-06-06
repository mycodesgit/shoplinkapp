<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'variation_id',
        'customer_id',
        'quantity',
        'price'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
    
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }
    
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->price * $this->quantity, 2);
    }
}