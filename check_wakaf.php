<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check wakaf ticket type
$wakaf = \App\Models\TicketType::where('name', 'wakaf')->first();

if ($wakaf) {
    echo "✅ Found wakaf ticket type:\n";
    echo "ID: " . $wakaf->id . "\n";
    echo "Name: " . $wakaf->name . "\n";
    echo "Price: " . $wakaf->price . "\n";
    echo "Active: " . ($wakaf->is_active ? 'Yes' : 'No') . "\n";
    echo "Race Category ID: " . $wakaf->race_category_id . "\n";
} else {
    echo "❌ No wakaf ticket type found\n";
    
    // Try to create one
    try {
        $raceCategory = \App\Models\RaceCategory::first();
        if ($raceCategory) {
            $wakaf = \App\Models\TicketType::create([
                'name' => 'wakaf',
                'price' => 25000,
                'is_active' => true,
                'race_category_id' => $raceCategory->id,
                'description' => 'Wakaf 5K Registration',
            ]);
            
            echo "✅ Created wakaf ticket type with ID: " . $wakaf->id . "\n";
        } else {
            echo "❌ No race categories found\n";
        }
    } catch (Exception $e) {
        echo "❌ Error creating wakaf ticket: " . $e->getMessage() . "\n";
    }
}

echo "\nChecking RandomPasswordService...\n";
if (class_exists('\App\Services\RandomPasswordService')) {
    echo "✅ RandomPasswordService class exists\n";
} else {
    echo "❌ RandomPasswordService class not found\n";
}

echo "\nDone!\n";
