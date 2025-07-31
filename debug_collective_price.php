<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG COLLECTIVE PRICE VALIDATION ===" . PHP_EOL;

// Check active ticket types
echo "1. Active Ticket Types:" . PHP_EOL;
$ticketTypes = \App\Models\TicketType::with('raceCategory')->where('is_active', true)->get();

if ($ticketTypes->isEmpty()) {
    echo "   ERROR: No active ticket types found!" . PHP_EOL;
} else {
    foreach ($ticketTypes as $ticket) {
        echo "   ID: {$ticket->id}" . PHP_EOL;
        echo "   Name: {$ticket->name}" . PHP_EOL;
        echo "   Price: Rp " . number_format($ticket->price, 0, ',', '.') . PHP_EOL;
        echo "   Category: " . ($ticket->raceCategory->name ?? 'NO_CATEGORY') . PHP_EOL;
        echo "   Active: " . ($ticket->is_active ? 'YES' : 'NO') . PHP_EOL;
        echo "   ---" . PHP_EOL;
    }
}

// Test XenditService method
echo PHP_EOL . "2. Testing XenditService getCollectivePrice method:" . PHP_EOL;
$xenditService = new \App\Services\XenditService();

$testCategories = ['5K', '10K', 'HM 21K'];

foreach ($testCategories as $category) {
    echo "   Testing category: {$category}" . PHP_EOL;
    try {
        $price = $xenditService->getCollectivePrice($category);
        if ($price === false) {
            echo "     RESULT: FALSE (validation failed)" . PHP_EOL;
        } else {
            echo "     RESULT: Rp " . number_format($price, 0, ',', '.') . PHP_EOL;
        }
    } catch (\Exception $e) {
        echo "     ERROR: " . $e->getMessage() . PHP_EOL;
    }
    echo "   ---" . PHP_EOL;
}

// Test AuthController method (simulate directly with model query)
echo PHP_EOL . "3. Testing getCollectiveTicketTypeForCategory logic:" . PHP_EOL;

foreach ($testCategories as $category) {
    echo "   Testing category: {$category}" . PHP_EOL;
    try {
        // Simulate the exact query from getCollectiveTicketTypeForCategory
        $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($category) {
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
        
        if ($ticketType) {
            echo "     RESULT: Found collective ticket type" . PHP_EOL;
            echo "     ID: {$ticketType->id}" . PHP_EOL;
            echo "     Name: {$ticketType->name}" . PHP_EOL;
            echo "     Price: Rp " . number_format($ticketType->price, 0, ',', '.') . PHP_EOL;
        } else {
            echo "     RESULT: No collective ticket type found (fallback to regular)" . PHP_EOL;
            
            // Fallback query
            $regularTicket = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($category) {
                $query->where('name', $category);
            })
            ->where('is_active', true)
            ->first();
            
            if ($regularTicket) {
                echo "     FALLBACK: Found regular ticket" . PHP_EOL;
                echo "     ID: {$regularTicket->id}" . PHP_EOL;
                echo "     Name: {$regularTicket->name}" . PHP_EOL;
                echo "     Price: Rp " . number_format($regularTicket->price, 0, ',', '.') . PHP_EOL;
            }
        }
    } catch (\Exception $e) {
        echo "     ERROR: " . $e->getMessage() . PHP_EOL;
    }
    echo "   ---" . PHP_EOL;
}

// Test price comparison
echo PHP_EOL . "4. Testing price comparison between methods:" . PHP_EOL;
foreach ($testCategories as $category) {
    echo "   Category: {$category}" . PHP_EOL;
    
    $xenditPrice = $xenditService->getCollectivePrice($category);
    
    // Simulate AuthController logic
    $authTicketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($category) {
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
    
    echo "     XenditService price: " . ($xenditPrice === false ? 'FALSE' : 'Rp ' . number_format($xenditPrice, 0, ',', '.')) . PHP_EOL;
    echo "     AuthController ticket price: " . ($authTicketType ? 'Rp ' . number_format($authTicketType->price, 0, ',', '.') : 'NULL') . PHP_EOL;
    
    if ($xenditPrice !== false && $authTicketType) {
        $match = (float)$xenditPrice === (float)$authTicketType->price;
        echo "     PRICES MATCH: " . ($match ? 'YES' : 'NO') . PHP_EOL;
        if (!$match) {
            echo "     DIFFERENCE: Rp " . number_format(abs($xenditPrice - $authTicketType->price), 0, ',', '.') . PHP_EOL;
        }
    }
    echo "   ---" . PHP_EOL;
}

echo PHP_EOL . "=== END DEBUG ===" . PHP_EOL;
