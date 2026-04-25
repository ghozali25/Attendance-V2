<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class MigrateController extends Controller
{
    public function run(Request $request)
    {
        // Simple security check - require a secret token
        $token = $request->header('X-Migrate-Token') ?? $request->get('token');
        $expectedToken = env('MIGRATE_TOKEN', 'your-secret-migrate-token');

        if ($token !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            Log::info('Starting database migrations');

            Artisan::call('migrate', [
                '--force' => true,
                '--no-interaction' => true,
            ]);

            $output = Artisan::output();

            Log::info('Migrations completed', ['output' => $output]);

            return response()->json([
                'success' => true,
                'message' => 'Migrations completed successfully',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error('Migration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function seed(Request $request)
    {
        // Simple security check - require a secret token
        $token = $request->header('X-Migrate-Token') ?? $request->get('token');
        $expectedToken = env('MIGRATE_TOKEN', 'your-secret-migrate-token');

        if ($token !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            Log::info('Starting database seeding');

            // Try to run specific seeders individually to identify which one fails
            $seeders = [
                'AdminSeeder',
                'SettingSeeder',
                'HolidaySeeder',
                'JobLevelSeeder',
                'PayrollComponentSeeder',
                'KpiSeeder',
            ];

            $results = [];
            foreach ($seeders as $seeder) {
                try {
                    Log::info("Running seeder: {$seeder}");
                    Artisan::call('db:seed', [
                        '--class' => "Database\\Seeders\\{$seeder}",
                        '--force' => true,
                        '--no-interaction' => true,
                    ]);
                    $output = Artisan::output();
                    $results[$seeder] = ['status' => 'success', 'output' => $output];
                    Log::info("Seeder {$seeder} completed", ['output' => $output]);
                } catch (\Exception $e) {
                    $results[$seeder] = ['status' => 'failed', 'error' => $e->getMessage()];
                    Log::error("Seeder {$seeder} failed", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            $allSuccess = collect($results)->every(fn($r) => $r['status'] === 'success');

            if ($allSuccess) {
                Log::info('All seeders completed successfully');
                return response()->json([
                    'success' => true,
                    'message' => 'Database seeding completed successfully',
                    'results' => $results,
                ]);
            } else {
                Log::warning('Some seeders failed', ['results' => $results]);
                return response()->json([
                    'success' => false,
                    'message' => 'Some seeders failed',
                    'results' => $results,
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Seeding process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
