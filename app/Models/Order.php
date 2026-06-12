<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'subtotal',
        'tax',
        'delivery_fee',
        'discount',
        'total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_method',
        'delivery_address',
        'delivery_instructions',
        'order_placed_at',
        'accepted_at',
        'ready_at',
        'delivered_at',
        'cancelled_at',
        'estimated_minutes',
        'payment_method',
        'payment_status',
        'payment_details',
        'transaction_id',
        'notes',
        'cancellation_reason'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'order_placed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'estimated_minutes' => 'integer',
        'payment_details' => 'array'
    ];

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY_TO_CLAIM = 'ready_to_claim';
    const STATUS_READY_TO_DELIVER = 'ready_to_deliver';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Delivery Method Constants
    const DELIVERY_PICKUP = 'pickup';
    const DELIVERY_DELIVERY = 'delivery';

    // Payment Method Constants
    const PAYMENT_CASH = 'cash';
    const PAYMENT_GCASH = 'gcash';
    const PAYMENT_MAYA = 'maya';
    const PAYMENT_CARD = 'card';

    // Payment Status Constants
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'variant_id');
    }

    // Helper Methods
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }

    public function getFormattedTaxAttribute()
    {
        return '₱' . number_format($this->tax, 2);
    }

    public function getFormattedDeliveryFeeAttribute()
    {
        return '₱' . number_format($this->delivery_fee, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => ['class' => 'bg-amber-100 text-amber-800', 'icon' => 'fa-hourglass-half', 'text' => 'Pending'],
            self::STATUS_ACCEPTED => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check-circle', 'text' => 'Accepted'],
            self::STATUS_PREPARING => ['class' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-utensils', 'text' => 'Preparing'],
            self::STATUS_READY_TO_CLAIM => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-box-open', 'text' => 'Ready to Claim'],
            self::STATUS_READY_TO_DELIVER => ['class' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-truck', 'text' => 'Ready to Deliver'],
            self::STATUS_OUT_FOR_DELIVERY => ['class' => 'bg-indigo-100 text-indigo-800', 'icon' => 'fa-motorcycle', 'text' => 'Out for Delivery'],
            self::STATUS_DELIVERED => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-check-double', 'text' => 'Delivered'],
            self::STATUS_CANCELLED => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fa-ban', 'text' => 'Cancelled'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-question', 'text' => ucfirst($this->status)],
        };
    }

    public function getPaymentMethodBadgeAttribute()
    {
        return match($this->payment_method) {
            self::PAYMENT_CASH => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fa-money-bill-wave', 'text' => 'Cash'],
            self::PAYMENT_GCASH => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fab fa-cc-visa', 'text' => 'GCash'],
            self::PAYMENT_MAYA => ['class' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-wallet', 'text' => 'Maya'],
            self::PAYMENT_CARD => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-credit-card', 'text' => 'Card'],
            default => ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-question', 'text' => ucfirst($this->payment_method)],
        };
    }

    public function getEstimatedTimeDisplayAttribute()
    {
        if (!$this->estimated_minutes) return null;
        
        if ($this->estimated_minutes <= 20) {
            return "Ready in {$this->estimated_minutes} min";
        }
        
        $range = $this->estimated_minutes;
        $min = $range - 5;
        $max = $range + 5;
        return "Ready in {$min}-{$max} min";
    }

    public function canCancel()
    {
        return in_array($this->status, [
            self::STATUS_PENDING, 
            self::STATUS_ACCEPTED
        ]);
    }

    public function canModify()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED
        ]);
    }

    // Boot method to auto-generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(4)) . rand(100, 999);
            }
        });
    }
}
