<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

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

    $response = new JsonResponse($data, $exitCode === 0 ? 200 : 500);
    
    // Send response and exit
    $response->send();
    exit($exitCode);
    
} catch (\Exception $e) {
    $errorData = [
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ];

    $errorResponse = new JsonResponse($errorData, 500);
    $errorResponse->send();
    exit(1);
}
