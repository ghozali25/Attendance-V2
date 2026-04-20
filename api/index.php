<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

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
        @mkdir($directory, 0755, true); // @ suppresses errors if multiple processes create at same time
    }
}

try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    error_log((string) $e);
    throw $e;
}
