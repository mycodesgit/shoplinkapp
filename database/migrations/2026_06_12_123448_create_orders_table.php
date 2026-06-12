<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-9823 format
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'pending', 
                'accepted', 
                'preparing', 
                'ready_to_claim', 
                'ready_to_deliver', 
                'out_for_delivery', 
                'delivered', 
                'cancelled'
            ])->default('pending');
            
            // Order amount details
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Customer info snapshot (in case user changes profile later)
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Delivery/Pickup details
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_instructions')->nullable();
            
            // Timing
            $table->timestamp('order_placed_at')->useCurrent();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Estimated times
            $table->integer('estimated_minutes')->nullable(); // 25-35 min display
            
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'gcash', 'maya'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->json('payment_details')->nullable();
            $table->string('transaction_id')->nullable();
            
            // Additional info
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('status');
            $table->index('order_number');
            $table->index('customer_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
