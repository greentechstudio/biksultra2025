<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FINDING THE DISCREPANCY USER ===\n\n";

// Find users with race_category mismatch
$usersWithMismatch = \App\Models\User::where('payment_confirmed', true)
    ->whereNotNull('ticket_type_id')
    ->whereNotNull('race_category')
    ->with(['ticketType.raceCategory'])
    ->get()
    ->filter(function($user) {
        if (!$user->ticketType || !$user->ticketType->raceCategory) {
            return false;
        }
        
        // Compare user's race_category (string) with ticket type's race category name
        return $user->race_category !== $user->ticketType->raceCategory->name;
    });

if ($usersWithMismatch->count() > 0) {
    echo "🚨 FOUND " . $usersWithMismatch->count() . " USER(S) WITH RACE CATEGORY MISMATCH:\n\n";
    
    foreach ($usersWithMismatch as $user) {
        echo "USER ID: " . $user->id . "\n";
        echo "Name: " . $user->name . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Payment Confirmed: " . ($user->payment_confirmed ? 'YES' : 'NO') . "\n";
        echo "User Race Category: '" . $user->race_category . "'\n";
        echo "Ticket Type ID: " . $user->ticket_type_id . " (" . ($user->ticketType ? $user->ticketType->name : 'NULL') . ")\n";
        echo "Ticket Type Race Category: '" . ($user->ticketType && $user->ticketType->raceCategory ? $user->ticketType->raceCategory->name : 'NULL') . "'\n";
        echo "Registration Date: " . $user->created_at . "\n";
        echo "---\n";
    }
    
    echo "\n💡 RECOMMENDED FIXES:\n";
    echo "1. Update user's race_category to match ticket_type's race_category name\n";
    echo "2. Or update user's ticket_type_id to match current race_category\n";
    echo "3. Check registration process to prevent future mismatches\n\n";
    
    // Suggest fix query
    foreach ($usersWithMismatch as $user) {
        $correctRaceCategory = $user->ticketType->raceCategory->name;
        echo "FIX QUERY for User ID " . $user->id . ":\n";
        echo "UPDATE users SET race_category = '" . $correctRaceCategory . "' WHERE id = " . $user->id . ";\n\n";
    }
    
} else {
    echo "✅ No users found with race category mismatch.\n";
    echo "The discrepancy might be due to other factors. Investigating further...\n\n";
    
    // Check other possible causes
    echo "🔍 CHECKING OTHER POSSIBLE CAUSES:\n\n";
    
    // Users with NULL ticket_type but payment_confirmed
    $usersNullTicketType = \App\Models\User::where('payment_confirmed', true)
        ->whereNull('ticket_type_id')
        ->count();
    echo "Users with payment_confirmed=true but ticket_type_id=NULL: " . $usersNullTicketType . "\n";
    
    // Users with NULL race_category but payment_confirmed
    $usersNullRaceCategory = \App\Models\User::where('payment_confirmed', true)
        ->whereNull('race_category')
        ->count();
    echo "Users with payment_confirmed=true but race_category=NULL: " . $usersNullRaceCategory . "\n";
    
    // Check if ticket_type exists but is deleted
    $usersInvalidTicketType = \App\Models\User::where('payment_confirmed', true)
        ->whereNotNull('ticket_type_id')
        ->whereDoesntHave('ticketType')
        ->count();
    echo "Users with invalid ticket_type_id reference: " . $usersInvalidTicketType . "\n";
    
    // Check users where role is admin (might be excluded differently)
    $adminUsers = \App\Models\User::where('payment_confirmed', true)
        ->where('role', 'admin')
        ->count();
    echo "Admin users with payment_confirmed=true: " . $adminUsers . "\n";
}

echo "\n=== DETAILED COUNT VERIFICATION ===\n";

// Dashboard calculation (exclude admins)
$dashboardPaidCount = \App\Models\User::where('payment_confirmed', true)
    ->where('role', '!=', 'admin')
    ->count();
echo "Dashboard Paid Count (excluding admins): " . $dashboardPaidCount . "\n";

// Category stats calculation
$categoryStatsPaidCount = 0;
$raceCategories = \App\Models\RaceCategory::with('ticketTypes')->get();

foreach($raceCategories as $raceCategory) {
    foreach($raceCategory->ticketTypes as $ticketType) {
        $count = \App\Models\User::where('payment_confirmed', true)
            ->where('race_category', $raceCategory->name)
            ->where('ticket_type_id', $ticketType->id)
            ->where('role', '!=', 'admin')
            ->count();
        
        if ($count > 0) {
            echo "Category " . $raceCategory->name . " + Ticket " . $ticketType->name . ": " . $count . " users\n";
            $categoryStatsPaidCount += $count;
        }
    }
}

echo "Category Stats Paid Count: " . $categoryStatsPaidCount . "\n";
echo "Difference: " . ($dashboardPaidCount - $categoryStatsPaidCount) . "\n";

echo "\n=== DISCREPANCY ANALYSIS COMPLETE ===\n";
