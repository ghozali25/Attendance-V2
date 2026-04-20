<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add calibration fields to appraisals for multi-level approval
        Schema::table('appraisals', function (Blueprint $table) {
            $table->char('calibrator_id', 26)->nullable()->after('evaluator_id');
            $table->enum('calibration_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            $table->text('calibration_notes')->nullable()->after('calibration_status');
        });

        // 2. Seed appraisal period lock settings
        \App\Models\Setting::firstOrCreate(
            ['key' => 'appraisal.period_open'],
            ['value' => '0', 'group' => 'Appraisal', 'type' => 'boolean', 'description' => 'Whether the appraisal window is currently open for submissions']
        );
        \App\Models\Setting::firstOrCreate(
            ['key' => 'appraisal.period_label'],
            ['value' => 'Q1 2026', 'group' => 'Appraisal', 'type' => 'text', 'description' => 'Label for the active appraisal period (e.g. Q1 2026, Semester 1)']
        );
        \App\Models\Setting::firstOrCreate(
            ['key' => 'appraisal.period_deadline'],
            ['value' => now()->endOfMonth()->toDateString(), 'group' => 'Appraisal', 'type' => 'date', 'description' => 'Deadline date for current appraisal window. After this date, submissions are locked.']
        );
    }

    public function down(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn(['calibrator_id', 'calibration_status', 'calibration_notes']);
        });

        \App\Models\Setting::whereIn('key', [
            'appraisal.period_open',
            'appraisal.period_label',
            'appraisal.period_deadline',
        ])->delete();
    }
};
