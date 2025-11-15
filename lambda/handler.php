<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

try {
    $exitCode = Artisan::call('recipes:generate-reviews');
    $output = Artisan::output();

    $data = [
        'success' => $exitCode === 0,
        'message' => $exitCode === 0 ? 'Reviews generated successfully' : 'Command failed',
        'output' => trim($output),
        'exitCode' => $exitCode,
    ];

    // Use Laravel's Response helper to create JSON response
    $response = Response::json($data, $exitCode === 0 ? 200 : 500);
    
    // Get the JSON content without sending headers
    echo $response->getContent();
    exit($exitCode);
    
} catch (\Throwable $e) {
    $errorData = [
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ];

    $errorResponse = Response::json($errorData, 500);
    echo $errorResponse->getContent();
    exit(1);
}
