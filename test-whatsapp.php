<?php

/*
 * Test script untuk validasi API WhatsApp
 * Jalankan dengan: php test-whatsapp.php
 */

require_once 'vendor/autoload.php';

function testWhatsAppAPI($number) {
    $apiKey = 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec';
    $sender = '628114040707';
    $apiUrl = 'https://wamd.system112.org/check-number';
    
    echo "Testing WhatsApp API for number: $number\n";
    echo "API URL: $apiUrl\n";
    echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
    echo "Sender: $sender\n";
    echo str_repeat("-", 50) . "\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . '?' . http_build_query([
        'api_key' => $apiKey,
        'sender' => $sender,
        'number' => $number
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    
    if ($error) {
        echo "CURL Error: $error\n";
        return false;
    }
    
    echo "Raw Response: $response\n";
    
    $data = json_decode($response, true);
    if ($data === null) {
        echo "Failed to parse JSON response\n";
        return false;
    }
    
    echo "Parsed Response: " . print_r($data, true) . "\n";
    
    // Check if valid - Handle API response format: {"status": true, "data": {"jid": "...", "exists": true}}
    $isValid = false;
    if (isset($data['status']) && $data['status'] === true) {
        // API returns status: true for successful requests
        if (isset($data['data']['exists']) && $data['data']['exists'] === true) {
            $isValid = true;
        } else {
            $isValid = false;
        }
    } else {
        // Handle case where status is false or missing
        $isValid = false;
    }
    
    echo "Is Valid WhatsApp Number: " . ($isValid ? "YES" : "NO") . "\n";
    echo str_repeat("=", 50) . "\n\n";
    
    return $isValid;
}

// Test dengan beberapa nomor
$testNumbers = [
    '628114040707', // sender number
    '628123456789',  // test number
    '6281234567890', // another test number
];

foreach ($testNumbers as $number) {
    testWhatsAppAPI($number);
    sleep(2); // Delay between requests
}

echo "Test completed!\n";
