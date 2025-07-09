<?php

// Test script untuk menguji fitur auto-resolution location
echo "=== Testing Location Auto-Resolution ===\n";

// Test data dengan hanya city name
$testData = [
    "name" => "Test Location Resolution",
    "email" => "testlocation@example.com",
    "phone" => "8114000810",
    "category" => "5K",
    "bib_name" => "LOCATION",
    "gender" => "Laki-laki",
    "birth_place" => "Kendari", // Menggunakan nama kota saja
    "birth_date" => "1990-01-01",
    "address" => "jl. test location",
    "city" => "Kendari", // Field city untuk auto-resolution
    "jersey_size" => "L",
    "whatsapp_number" => "8114000810",
    "emergency_contact_name" => "emergency location",
    "emergency_contact_phone" => "08112212990",
    "group_community" => "LOCATION_TEST",
    "blood_type" => "A",
    "occupation" => "tester",
    "medical_history" => "sehat",
    "event_source" => "Location Test"
];

echo "1. Testing smart location search API...\n";

// Test smart search API
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "http://localhost/api/location/smart-search?q=Kendari",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        "Accept: application/json"
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "Smart Search HTTP Code: " . $httpCode . "\n";
echo "Smart Search Response: " . $response . "\n";

$searchData = json_decode($response, true);
if ($searchData && $searchData['success']) {
    echo "✅ Smart search working! Found " . count($searchData['data']) . " results\n";
    echo "Best match: " . $searchData['data'][0]['full_name'] . "\n";
} else {
    echo "❌ Smart search failed\n";
}

echo "\n2. Testing registration with auto-resolution...\n";

// Test registration API
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://www.amazingsultrarun.com/api/register",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "Registration HTTP Code: " . $httpCode . "\n";
echo "Registration Response: " . $response . "\n";

$regData = json_decode($response, true);
if ($regData && $regData['success']) {
    echo "✅ Registration successful with auto-resolution!\n";
    echo "Registration Number: " . $regData['data']['registration_number'] . "\n";
} else {
    echo "❌ Registration failed\n";
    if (isset($regData['message'])) {
        echo "Error: " . $regData['message'] . "\n";
    }
}

echo "\n=== Usage Examples ===\n";
echo "Request dengan city field:\n";
echo "{\n";
echo "  \"name\": \"John Doe\",\n";
echo "  \"email\": \"john@example.com\",\n";
echo "  \"phone\": \"081234567890\",\n";
echo "  \"category\": \"5K\",\n";
echo "  \"city\": \"Kendari\",\n";
echo "  \"birth_place\": \"Bau-bau\",\n";
echo "  // ... field lainnya\n";
echo "}\n\n";

echo "Sistem akan otomatis resolve:\n";
echo "- regency_id: ID kabupaten/kota\n";
echo "- regency_name: Nama kabupaten/kota\n";
echo "- province_name: Nama provinsi\n\n";

echo "Prioritas auto-resolution:\n";
echo "1. Gunakan regency_id, regency_name, province_name jika sudah ada\n";
echo "2. Jika tidak ada, coba resolve dari field 'city'\n";
echo "3. Jika masih tidak ada, coba resolve dari 'birth_place'\n";
echo "4. Jika masih gagal, biarkan kosong\n";
