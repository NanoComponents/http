<?php

include 'vendor/autoload.php';

use Nano\Http\Request;

$_FILES = [
    'images' => [
        'name' => ['photo1.jpg', 'photo2.jpg', 'photo3.png'],
        'type' => ['image/jpeg', 'image/jpeg', 'image/png'],
        'tmp_name' => ['/tmp/phpYzdqkD', '/tmp/phpUjd9Q8', '/tmp/phpK7lTg2'],
        'full_path' => ['photo1.jpg', 'photo2.jpg', 'photo3.png'],
        'error' => [0, 0, 0],
        'size' => [500000, 700000, 250000]
    ]
];

function benchmarkRequest(int $iterations = 10000)
{
    $start = microtime(true);
    $memoryStart = memory_get_usage();

    for ($i = 0; $i < $iterations; $i++) {
        $request = Request::initialize(); // Simulating a full request lifecycle
        $result = $request->getFile()->getName();
        unset($request, $result); // Free memory for each iteration
    }

    $end = microtime(true);
    $memoryEnd = memory_get_usage();

    return [
        'iterations' => $iterations,
        'execution_time_ms' => ($end - $start) * 1000, // Convert seconds to milliseconds
        'memory_usage_bytes' => $memoryEnd - $memoryStart
    ];
}

$result = benchmarkRequest();
echo '<pre>';
print_r($result);
echo '</pre>';