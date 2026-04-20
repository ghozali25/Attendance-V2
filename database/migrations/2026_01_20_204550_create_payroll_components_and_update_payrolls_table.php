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
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['allowance', 'deduction']);
            $table->enum('calculation_type', ['fixed', 'percentage_basic', 'daily_presence']);
            $table->decimal('amount', 15, 2)->nullable()->comment('Fixed amount or daily rate');
            $table->decimal('percentage', 5, 2)->nullable()->comment('Percentage (0-100)');
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('type', ['regular', 'special'])->default('regular')->after('user_id');
            $table->json('details')->nullable()->after('net_salary')->comment('Snapshot of calculation details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['type', 'details']);
        });

        Schema::dropIfExists('payroll_components');
    }
};
