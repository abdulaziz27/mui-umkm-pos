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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete(); // The cashier
            
            $table->decimal('starting_cash', 12, 2)->default(0); // Modal Awal
            $table->decimal('expected_ending_cash', 12, 2)->nullable(); // Dihitung oleh sistem
            $table->decimal('actual_ending_cash', 12, 2)->nullable(); // Diinput fisik oleh kasir saat tutup shift
            $table->decimal('difference', 12, 2)->nullable(); // actual - expected
            
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
