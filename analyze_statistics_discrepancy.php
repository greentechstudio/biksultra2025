<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANALYZING CATEGORY STATISTICS VS PAID REGISTRATIONS DISCREPANCY ===\n\n";

// 1. Calculate Paid Registrations (from Dashboard)
$paidRegistrations = \App\Models\User::where('role', '!=', 'admin')
    ->where('payment_confirmed', true)
    ->count();

echo "📊 DASHBOARD CALCULATION:\n";
echo "Paid Registrations: {$paidRegistrations}\n";
echo "Logic: Users where payment_confirmed = true (excluding admins)\n\n";

// 2. Calculate Category Stats by Ticket Type (recreate the logic)
$categories = \App\Models\RaceCategory::all();
$totalPaidFromCategoryStats = 0;

echo "📊 CATEGORY STATISTICS BY TICKET TYPE:\n";

foreach ($categories as $category) {
    $ticketTypes = \App\Models\TicketType::where('race_category_id', $category->id)->get();
    
    foreach ($ticketTypes as $ticketType) {
        // This is the logic from getCategoryStatsByTicketType()
        $paidCount = \App\Models\User::where('role', '!=', 'admin')
            ->where('race_category', $category->name)
            ->where('ticket_type_id', $ticketType->id)
            ->where('payment_confirmed', true)
            ->count();
        
        $totalCount = \App\Models\User::where('role', '!=', 'admin')
            ->where('race_category', $category->name)
            ->where('ticket_type_id', $ticketType->id)
            ->count();
        
        if ($totalCount > 0) {
            echo "  {$category->name} ({$ticketType->name}): {$paidCount} paid / {$totalCount} total\n";
            $totalPaidFromCategoryStats += $paidCount;
        }
    }
}

echo "\nTotal Paid from Category Stats: {$totalPaidFromCategoryStats}\n\n";

// 3. Check the discrepancy
$discrepancy = $paidRegistrations - $totalPaidFromCategoryStats;

echo "🔍 DISCREPANCY ANALYSIS:\n";
echo "Dashboard Paid Registrations: {$paidRegistrations}\n";
echo "Category Stats Paid Total: {$totalPaidFromCategoryStats}\n";
echo "Discrepancy: {$discrepancy}\n\n";

if ($discrepancy != 0) {
    echo "🚨 POTENTIAL CAUSES OF DISCREPANCY:\n\n";
    
    // Check users with payment_confirmed=true but no ticket_type_id
    $usersWithoutTicketType = \App\Models\User::where('role', '!=', 'admin')
        ->where('payment_confirmed', true)
        ->whereNull('ticket_type_id')
        ->count();
    
    echo "1. Users with payment_confirmed=true but ticket_type_id=NULL: {$usersWithoutTicketType}\n";
    
    // Check users with payment_confirmed=true but race_category doesn't match any category
    $validCategories = $categories->pluck('name')->toArray();
    $usersWithInvalidCategory = \App\Models\User::where('role', '!=', 'admin')
        ->where('payment_confirmed', true)
        ->whereNotIn('race_category', $validCategories)
        ->count();
    
    echo "2. Users with payment_confirmed=true but invalid race_category: {$usersWithInvalidCategory}\n";
    
    // Check users with payment_confirmed=true but ticket_type_id points to non-existent ticket
    $validTicketTypeIds = \App\Models\TicketType::pluck('id')->toArray();
    $usersWithInvalidTicketType = \App\Models\User::where('role', '!=', 'admin')
        ->where('payment_confirmed', true)
        ->whereNotNull('ticket_type_id')
        ->whereNotIn('ticket_type_id', $validTicketTypeIds)
        ->count();
    
    echo "3. Users with payment_confirmed=true but invalid ticket_type_id: {$usersWithInvalidTicketType}\n";
    
    // Check for race_category vs ticket_type mismatch
    $mismatchedUsers = \App\Models\User::where('role', '!=', 'admin')
        ->where('payment_confirmed', true)
        ->whereNotNull('ticket_type_id')
        ->get()
        ->filter(function($user) {
            if (!$user->ticketType || !$user->ticketType->raceCategory) {
                return true;
            }
            return $user->race_category !== $user->ticketType->raceCategory->name;
        })
        ->count();
    
    echo "4. Users with race_category mismatch with ticket_type: {$mismatchedUsers}\n\n";
    
    if ($usersWithoutTicketType > 0) {
        echo "👤 USERS WITHOUT TICKET TYPE:\n";
        $problematicUsers = \App\Models\User::where('role', '!=', 'admin')
            ->where('payment_confirmed', true)
            ->whereNull('ticket_type_id')
            ->take(5)
            ->get(['id', 'name', 'email', 'race_category', 'ticket_type_id', 'created_at']);
        
        foreach ($problematicUsers as $user) {
            echo "  ID: {$user->id}, Email: {$user->email}, Race: {$user->race_category}, Ticket: NULL\n";
        }
        echo "\n";
    }
    
    if ($usersWithInvalidCategory > 0) {
        echo "👤 USERS WITH INVALID CATEGORY:\n";
        $problematicUsers = \App\Models\User::where('role', '!=', 'admin')
            ->where('payment_confirmed', true)
            ->whereNotIn('race_category', $validCategories)
            ->take(5)
            ->get(['id', 'name', 'email', 'race_category', 'ticket_type_id']);
        
        foreach ($problematicUsers as $user) {
            echo "  ID: {$user->id}, Email: {$user->email}, Race: {$user->race_category}, Ticket: {$user->ticket_type_id}\n";
        }
        echo "\n";
    }
} else {
    echo "✅ NO DISCREPANCY - Both calculations match!\n\n";
}

echo "=== CALCULATION DIFFERENCES ===\n";
echo "Dashboard Logic: COUNT(*) WHERE payment_confirmed = true\n";
echo "Category Stats Logic: SUM of COUNT(*) per category+ticket_type WHERE payment_confirmed = true\n";
echo "\nThe difference suggests data inconsistency in user records.\n";
