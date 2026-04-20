<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check");
            DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('present', 'late', 'excused', 'sick', 'absent', 'rejected'))");
        } else {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('present', 'late', 'excused', 'sick', 'absent', 'rejected') DEFAULT 'absent'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE attendances DROP CONSTRAINT IF EXISTS attendances_status_check");
            DB::statement("ALTER TABLE attendances ADD CONSTRAINT attendances_status_check CHECK (status IN ('present', 'late', 'excused', 'sick', 'absent'))");
        } else {
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('present', 'late', 'excused', 'sick', 'absent') DEFAULT 'absent'");
        }
    }
};
