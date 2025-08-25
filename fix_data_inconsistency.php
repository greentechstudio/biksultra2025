 <?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING DATA INCONSISTENCY ===\n\n";

// Get the problematic user
$user = \App\Models\User::find(2109);

if ($user) {
    echo "BEFORE FIX:\n";
    echo "User ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Race Category: " . $user->race_category . "\n";
    echo "Ticket Type ID: " . $user->ticket_type_id . "\n";
    
    // Fix: Update race_category to match ticket_type's race_category
    $ticketType = \App\Models\TicketType::find($user->ticket_type_id);
    if ($ticketType && $ticketType->raceCategory) {
        $correctRaceCategory = $ticketType->raceCategory->name;
        
        echo "\nApplying fix...\n";
        echo "Setting race_category to: " . $correctRaceCategory . "\n";
        
        $user->race_category = $correctRaceCategory;
        $user->save();
        
        echo "\nAFTER FIX:\n";
        echo "User ID: " . $user->id . "\n";
        echo "Name: " . $user->name . "\n";
        echo "Race Category: " . $user->race_category . "\n";
        echo "Ticket Type ID: " . $user->ticket_type_id . "\n";
        
        echo "\n✅ User data fixed successfully!\n";
    }
} else {
    echo "User not found.\n";
}

echo "\n=== VERIFYING COUNTS AFTER FIX ===\n";

// Dashboard count
$dashboardCount = \App\Models\User::where('payment_confirmed', true)
    ->where('role', '!=', 'admin')
    ->count();
echo "Dashboard Paid Count: " . $dashboardCount . "\n";

// Category stats count (simulation)
$categoryStatsCount = 0;
$tickets = \App\Models\TicketType::with('raceCategory')->get();

foreach($tickets as $ticket) {
    if ($ticket->raceCategory) {
        $count = \App\Models\User::where('payment_confirmed', true)
            ->where('race_category', $ticket->raceCategory->name)
            ->where('ticket_type_id', $ticket->id)
            ->where('role', '!=', 'admin')
            ->count();
        
        if ($count > 0) {
            $categoryStatsCount += $count;
        }
    }
}

echo "Category Stats Count: " . $categoryStatsCount . "\n";
echo "Difference: " . ($dashboardCount - $categoryStatsCount) . "\n";

if ($dashboardCount == $categoryStatsCount) {
    echo "\n🎉 DISCREPANCY RESOLVED! Counts are now consistent.\n";
} else {
    echo "\n⚠️ There might be other data inconsistencies.\n";
}

echo "\n=== FIX COMPLETE ===\n";
