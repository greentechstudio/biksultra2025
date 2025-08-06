<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Jersey Sizes ===\n";
$jerseySizes = \App\Models\JerseySize::all();
foreach ($jerseySizes as $jersey) {
    echo "ID: {$jersey->id}\n";
    echo "Data: " . json_encode($jersey->toArray(), JSON_PRETTY_PRINT) . "\n";
    echo "Available fields: " . implode(', ', array_keys($jersey->toArray())) . "\n\n";
}

echo "=== Test jersey_size_id = 1 ===\n";
$testJersey = \App\Models\JerseySize::find(1);
if ($testJersey) {
    echo "Jersey found:\n";
    echo json_encode($testJersey->toArray(), JSON_PRETTY_PRINT) . "\n";
    
    // Test different possible field names
    $possibleFields = ['size', 'name', 'label', 'jersey_size', 'description'];
    foreach ($possibleFields as $field) {
        if (property_exists($testJersey, $field) || isset($testJersey->$field)) {
            echo "Field '{$field}': " . $testJersey->$field . "\n";
        }
    }
} else {
    echo "No jersey found with ID 1\n";
}
