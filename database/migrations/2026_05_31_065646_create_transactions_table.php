<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('cashier_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('receipt_number')->unique();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2); // subtotal - discount

            $table->decimal('platform_fee', 15, 2)->default(0); // MDR fee
            $table->decimal('net_amount', 15, 2); // total_amount - platform_fee

            $table->string('payment_method')->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('paid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
