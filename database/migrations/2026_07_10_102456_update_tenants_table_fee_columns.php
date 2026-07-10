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
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'mdr_fee_percentage')) {
                $table->dropColumn('mdr_fee_percentage');
            }
            if (Schema::hasColumn('tenants', 'platform_fee_type')) {
                $table->dropColumn('platform_fee_type');
            }
            if (Schema::hasColumn('tenants', 'platform_fee_fixed')) {
                $table->dropColumn('platform_fee_fixed');
            }

            if (!Schema::hasColumn('tenants', 'credit_balance')) {
                $table->decimal('credit_balance', 15, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('tenants', 'platform_fee_rate')) {
                $table->decimal('platform_fee_rate', 15, 2)->default(100)->after('credit_balance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'credit_balance')) {
                $table->dropColumn('credit_balance');
            }
            if (Schema::hasColumn('tenants', 'platform_fee_rate')) {
                $table->dropColumn('platform_fee_rate');
            }

            if (!Schema::hasColumn('tenants', 'mdr_fee_percentage')) {
                $table->decimal('mdr_fee_percentage', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('tenants', 'platform_fee_type')) {
                $table->enum('platform_fee_type', ['percentage', 'fixed'])->default('percentage');
            }
            if (!Schema::hasColumn('tenants', 'platform_fee_fixed')) {
                $table->decimal('platform_fee_fixed', 12, 2)->default(0);
            }
        });
    }
};
