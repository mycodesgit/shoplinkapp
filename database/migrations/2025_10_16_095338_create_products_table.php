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
            $table->integer('catid')->index();
            $table->integer('subcatid')->index();
            $table->string('prdctname');
            $table->text('prdctdesc')->nullable();
            $table->string('prdctsku')->unique();
            $table->decimal('prdctprice', 8, 2);
            $table->integer('prdctstock');
            $table->enum('pstatus', ['1', '2', '3'])->default('1');
            $table->text('prdctimage');
            $table->string('prdctslug')->unique();
            $table->string('prdctvariation');
            $table->string('prdcttag');
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
