<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

return function () {
    try {
        $exitCode = Artisan::call('recipes:generate-reviews');
        $output = Artisan::output();

        $data = [
            'success' => $exitCode === 0,
            'message' => $exitCode === 0 ? 'Reviews generated successfully' : 'Command failed',
            'output' => trim($output),
            'exitCode' => $exitCode,
        ];

        return [
            'statusCode' => $exitCode === 0 ? 200 : 500,
            'body' => json_encode($data),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    } catch (\Throwable $e) {
        return [
            'statusCode' => 500,
            'body' => json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }
};
