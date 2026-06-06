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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catid')->constrained('category')->onDelete('cascade');
            $table->integer('subcatid');
            $table->string('prdctname');
            $table->text('prdctdesc')->nullable();
            $table->text('prdctimage');
            $table->string('prdctslug')->unique();
            $table->string('prdcttag');
            $table->foreignId('postedBy')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_product_id')->constrained('products')->onDelete('cascade');
            $table->string('variation_name'); // e.g., "Color", "Size"
            $table->string('variation_value'); // e.g., "Black", "Gray"
            $table->string('variant_sku')->unique(); // Unique SKU per variation
            $table->decimal('variant_price', 8, 2); // Can override product price
            $table->integer('variant_stock'); // Stock per variation
            $table->string('variant_image')->nullable(); // Variation-specific image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
