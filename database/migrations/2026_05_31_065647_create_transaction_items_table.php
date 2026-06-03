<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignUuid('product_id')->nullable()->constrained('products')->nullOnDelete();

            // Snapshots of the product at the time of transaction
            $table->string('product_name');
            $table->enum('type', ['physical', 'service'])->default('physical');
            $table->decimal('price', 15, 2);

            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2); // price * quantity

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
