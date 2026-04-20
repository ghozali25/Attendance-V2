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
        Schema::table('job_titles', function (Blueprint $table) {
            $table->integer('level')->default(4)->after('name')->comment('1: Head, 2: Manager, 3: Senior, 4: Staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_titles', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
