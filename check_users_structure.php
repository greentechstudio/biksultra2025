<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING USERS TABLE STRUCTURE ===\n\n";

// Get table columns
$columns = \Schema::getColumnListing('users');
echo "Users table columns:\n";
foreach($columns as $column) {
    echo "- " . $column . "\n";
}

echo "\n=== SAMPLE USER DATA ===\n";
$sampleUser = \App\Models\User::first();
if ($sampleUser) {
    echo "Sample user attributes:\n";
    $attributes = $sampleUser->getAttributes();
    foreach($attributes as $key => $value) {
        echo "- " . $key . ": " . $value . "\n";
    }
}

echo "\n=== CHECKING USER MODEL RELATIONSHIPS ===\n";
echo "Available relationships in User model:\n";
$reflection = new ReflectionClass(\App\Models\User::class);
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

foreach($methods as $method) {
    if (strpos($method->getName(), 'raceCategory') !== false || 
        strpos($method->getName(), 'ticketType') !== false) {
        echo "- " . $method->getName() . "()\n";
    }
}
