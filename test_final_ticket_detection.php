<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TicketType;

echo "=== FINAL TICKET TYPE DETECTION TEST ===\n";

// Test the exact logic from CollectiveImportController
function detectTicketTypeId($raceCategory) {
    // Normalize race category
    $normalized = $raceCategory;
    if (in_array(strtolower($raceCategory), ['21k', 'half marathon', 'hm', '21 km'])) {
        $normalized = 'HM 21K';
    }
    
    echo "\n🏃 Input: '$raceCategory' -> Normalized: '$normalized'\n";
    
    // Try to find collective ticket first
    $collectiveTicket = TicketType::where('race_category', $normalized)
        ->whereRaw('LOWER(name) LIKE ?', ['%kolektif%'])
        ->where('is_active', true)
        ->first();
    
    if ($collectiveTicket) {
        echo "   ✅ COLLECTIVE FOUND: {$collectiveTicket->name} (ID: {$collectiveTicket->id})\n";
        return $collectiveTicket->id;
    }
    
    // Fallback to any active ticket
    $regularTicket = TicketType::where('race_category', $normalized)
        ->where('is_active', true)
        ->first();
    
    if ($regularTicket) {
        echo "   ⚠️  FALLBACK: {$regularTicket->name} (ID: {$regularTicket->id})\n";
        return $regularTicket->id;
    }
    
    echo "   ❌ NO TICKET FOUND!\n";
    return null;
}

// Test various inputs that might come from CSV
$testInputs = [
    '5K',
    '10K', 
    '21K',
    'Half Marathon',
    'HM 21K',
    '21 km',
    'hm',
    '42K'
];

foreach ($testInputs as $input) {
    $ticketId = detectTicketTypeId($input);
    if ($ticketId) {
        echo "   📝 RESULT: ticket_type_id = $ticketId\n";
    }
}

echo "\n=== CSV IMPORT SIMULATION ===\n";
$csvData = [
    ['name' => 'John Doe', 'race_category' => '21K'],
    ['name' => 'Jane Smith', 'race_category' => 'Half Marathon'],
    ['name' => 'Bob Wilson', 'race_category' => '5K'],
    ['name' => 'Alice Brown', 'race_category' => '10K']
];

foreach ($csvData as $row) {
    $ticketId = detectTicketTypeId($row['race_category']);
    echo "👤 {$row['name']} ({$row['race_category']}) -> ticket_type_id: " . ($ticketId ?: 'NULL') . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "Collective tickets should now be properly detected!\n";
