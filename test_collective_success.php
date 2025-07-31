<?php
// Simple test to check collective success data
include __DIR__ . '/vendor/autoload.php';

// Simulate the data structure
$successful_users = [
    (object)[
        'name' => 'Peserta 1',
        'email' => 'peserta1@example.com',
        'bib_name' => 'RUNNER1',
        'race_category_name' => '5K',
        'registration_fee' => 125000
    ],
    (object)[
        'name' => 'Peserta 2',
        'email' => 'peserta2@example.com',
        'bib_name' => 'RUNNER2',
        'race_category_name' => '10K',
        'registration_fee' => 150000
    ],
    (object)[
        'name' => 'Peserta 3',
        'email' => 'peserta3@example.com',
        'bib_name' => 'RUNNER3',
        'race_category_name' => '21K',
        'registration_fee' => 175000
    ]
];

echo "Testing array_sum and array_column...\n";

try {
    $total = array_sum(array_column($successful_users, 'registration_fee'));
    echo "Total calculated: Rp " . number_format($total, 0, ',', '.') . "\n";
    echo "Test successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Testing foreach loop...\n";
foreach ($successful_users as $index => $user) {
    echo ($index + 1) . ". " . $user->name . " - " . $user->race_category_name . " - Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
}

echo "All tests completed!\n";
