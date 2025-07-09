<?php

// Test endpoint smart-search di server
echo "Testing smart-search endpoint...\n";

// Test 1: Akses langsung ke endpoint
$url = "https://www.amazingsultrarun.com/api/location/smart-search?q=Sorong";
echo "URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "cURL Error: $error\n";
}
echo "Response: $response\n";

echo "\n" . str_repeat("-", 50) . "\n";

// Test 2: Test dengan Redis connection
echo "Testing Redis connection...\n";
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    
    $allRegenciesJson = $redis->get('all_regencies');
    
    if ($allRegenciesJson) {
        $allRegencies = json_decode($allRegenciesJson, true);
        echo "Redis data found: " . count($allRegencies) . " regencies\n";
        
        // Search for Sorong
        $found = array_filter($allRegencies, function($r) {
            return stripos($r['name'], 'Sorong') !== false;
        });
        
        echo "Regencies containing 'Sorong': " . count($found) . "\n";
        foreach ($found as $regency) {
            echo "- {$regency['name']}, {$regency['province_name']} (ID: {$regency['id']})\n";
        }
    } else {
        echo "No data found in Redis key 'all_regencies'\n";
    }
    
    $redis->close();
} catch (Exception $e) {
    echo "Redis Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("-", 50) . "\n";

// Test 3: Test internal HTTP call seperti di AuthController
echo "Testing internal HTTP call (like AuthController)...\n";

// Simulate the same call as in resolveLocationData
$baseUrl = "https://www.amazingsultrarun.com";
$apiUrl = $baseUrl . "/api/location/smart-search?q=Sorong";

echo "Internal API URL: $apiUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_USERAGENT, 'Laravel Internal Call');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "cURL Error: $error\n";
}

if ($response) {
    $data = json_decode($response, true);
    if ($data) {
        echo "Response parsed successfully\n";
        echo "Success: " . ($data['success'] ?? 'N/A') . "\n";
        if (isset($data['data']) && !empty($data['data'])) {
            echo "First result: " . json_encode($data['data'][0], JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "No data in response\n";
        }
    } else {
        echo "Failed to parse JSON response\n";
        echo "Raw response: $response\n";
    }
} else {
    echo "No response received\n";
}
