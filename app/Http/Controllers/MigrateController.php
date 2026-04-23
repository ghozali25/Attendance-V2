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
}
