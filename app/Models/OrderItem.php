<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'product_name',
        'variation_name',
        'quantity',
        'price',
        'subtotal',
        'special_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    // Helper Methods
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    public function getItemTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedItemTotalAttribute()
    {
        return '₱' . number_format($this->price * $this->quantity, 2);
    }

    // Automatically calculate subtotal before saving
    protected static function booted()
    {
        static::creating(function ($orderItem) {
            $orderItem->subtotal = $orderItem->price * $orderItem->quantity;
        });

        static::updating(function ($orderItem) {
            $orderItem->subtotal = $orderItem->price * $orderItem->quantity;
        });
    }
}
