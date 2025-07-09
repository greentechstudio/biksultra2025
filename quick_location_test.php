<?php

echo "=== Quick Test Location Resolution ===\n";

// Test registrasi dengan data sederhana
$testData = [
    "name" => "Test Auto Location",
    "email" => "testautolocation@example.com", 
    "phone" => "8114000811",
    "category" => "5K",
    "city" => "Kendari"
];

echo "Testing registration with city: Kendari\n";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "http://localhost/api/register",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$err = curl_error($curl);
curl_close($curl);

echo "HTTP Code: " . $httpCode . "\n";
if ($err) {
    echo "cURL Error: " . $err . "\n";
}

$responseData = json_decode($response, true);
if ($responseData) {
    echo "Response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
    if (isset($responseData['success']) && $responseData['success']) {
        echo "✅ Registration successful!\n";
        if (isset($responseData['data']['registration_number'])) {
            echo "Registration Number: " . $responseData['data']['registration_number'] . "\n";
        }
    } else {
        echo "❌ Registration failed\n";
        if (isset($responseData['message'])) {
            echo "Error: " . $responseData['message'] . "\n";
        }
    }
} else {
    echo "Raw Response: " . $response . "\n";
}
