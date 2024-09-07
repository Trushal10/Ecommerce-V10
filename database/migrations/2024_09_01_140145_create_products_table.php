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
            $table->string('title');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->double('price',10,2);
            $table->double('compare_price',10,2)->nullable();
            $table->foreignId('categories_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_categories_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('is_fe/atured',['YES','NO'])->default('NO');
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->enum('track_qty',['YES','NO'])->default('YES');
            $table->integer('qty')->nullable();
            $table->integer('status')->default(1);
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
