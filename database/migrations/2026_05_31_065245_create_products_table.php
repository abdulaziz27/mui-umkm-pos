<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('type', ['physical', 'service'])->default('physical');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('cost', 15, 2)->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->integer('minimum_stock')->nullable()->default(10); // Dinamis per produk
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
