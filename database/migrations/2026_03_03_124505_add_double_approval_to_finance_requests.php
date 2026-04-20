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
        Schema::table('cash_advances', function (Blueprint $table) {
            $table->ulid('head_approved_by')->nullable()->after('approved_by');
            $table->timestamp('head_approved_at')->nullable()->after('head_approved_by');
            $table->ulid('finance_approved_by')->nullable()->after('head_approved_at');
            $table->timestamp('finance_approved_at')->nullable()->after('finance_approved_by');
        });

        Schema::table('reimbursements', function (Blueprint $table) {
            $table->ulid('head_approved_by')->nullable()->after('approved_by');
            $table->timestamp('head_approved_at')->nullable()->after('head_approved_by');
            $table->ulid('finance_approved_by')->nullable()->after('head_approved_at');
            $table->timestamp('finance_approved_at')->nullable()->after('finance_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_advances', function (Blueprint $table) {
            $table->dropColumn([
                'head_approved_by',
                'head_approved_at',
                'finance_approved_by',
                'finance_approved_at',
            ]);
        });

        Schema::table('reimbursements', function (Blueprint $table) {
            $table->dropColumn([
                'head_approved_by',
                'head_approved_at',
                'finance_approved_by',
                'finance_approved_at',
            ]);
        });
    }
};
