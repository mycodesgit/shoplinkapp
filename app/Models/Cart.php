<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'product_id',
        'customer_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
        'price' => 'decimal:2'
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    // Accessor for subtotal
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    // Accessor for formatted subtotal
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->price * $this->quantity, 2);
    }

    // Get size from options
    public function getSizeAttribute()
    {
        $options = is_array($this->options) ? $this->options : json_decode($this->options, true);
        return $options['size'] ?? 'M';
    }
}
