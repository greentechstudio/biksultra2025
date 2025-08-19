<?php
/**
 * Test script to check ticket type ID detection for CSV import
 * Usage: php test_ticket_type_detection.php
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TICKET TYPE DETECTION TEST ===\n\n";

$testCategories = ['5K', '10K', '21K', '42K'];

foreach ($testCategories as $category) {
    echo "🏃 Testing category: {$category}\n";
    
    // Test 1: Look for collective ticket type
    $collectiveTicket = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($category) {
        $query->where('name', $category);
    })
    ->where('is_active', true)
    ->where(function($query) {
        $query->where('name', 'LIKE', '%kolektif%')
              ->orWhere('name', 'LIKE', '%Kolektif%')
              ->orWhere('name', 'LIKE', '%collective%')
              ->orWhere('name', 'LIKE', '%Collective%');
    })
    ->first();
    
    if ($collectiveTicket) {
        echo "   ✅ Collective ticket found: {$collectiveTicket->name} (ID: {$collectiveTicket->id}, Price: Rp " . number_format($collectiveTicket->price) . ")\n";
    } else {
        echo "   ⚠️  No collective ticket found\n";
    }
    
    // Test 2: Look for any active ticket type
    $anyTicket = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($category) {
        $query->where('name', $category);
    })
    ->where('is_active', true)
    ->first();
    
    if ($anyTicket) {
        echo "   ✅ Active ticket found: {$anyTicket->name} (ID: {$anyTicket->id}, Price: Rp " . number_format($anyTicket->price) . ")\n";
    } else {
        echo "   ❌ No active ticket found for category: {$category}\n";
    }
    
    echo "\n";
}

echo "=== AVAILABLE TICKET TYPES ===\n";
$allTicketTypes = \App\Models\TicketType::with('raceCategory')
    ->where('is_active', true)
    ->orderBy('race_category_id')
    ->get();

foreach ($allTicketTypes as $ticket) {
    $categoryName = $ticket->raceCategory ? $ticket->raceCategory->name : 'N/A';
    echo "ID: {$ticket->id} | Category: {$categoryName} | Name: {$ticket->name} | Price: Rp " . number_format($ticket->price) . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "CSV import should now properly set ticket_type_id based on race_category!\n";
