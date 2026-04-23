<?php

// Vercel Migration Script
// This script runs database migrations during Vercel deployment

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Fix Vercel Serverless read-only filesystem by setting the storage path to /tmp
$storagePath = '/tmp/storage';
$app->useStoragePath($storagePath);

// Create required storage directories on-the-fly for the serverless container
$directories = [
    "$storagePath/app",
    "$storagePath/framework/cache/data",
    "$storagePath/framework/sessions",
    "$storagePath/framework/testing",
    "$storagePath/framework/views",
    "$storagePath/logs",
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0755, true);
    }
}

// Create the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Run migrations
$status = $kernel->call('migrate', [
    '--force' => true,
    '--no-interaction' => true,
]);

if ($status === 0) {
    echo "✅ Migrations completed successfully\n";
    exit(0);
} else {
    echo "❌ Migrations failed\n";
    exit(1);
}
