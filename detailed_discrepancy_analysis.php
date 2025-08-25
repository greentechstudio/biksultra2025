<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DETAILED VERIFICATION OF DISCREPANCY ===\n\n";

// Get the problematic user
$user = \App\Models\User::with('ticketType')->find(2109);

if ($user) {
    echo "🚨 PROBLEMATIC USER DETAILS:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Race Category (user): " . $user->race_category . "\n";
    echo "Ticket Type ID: " . $user->ticket_type_id . "\n";
    echo "Ticket Type Name: " . ($user->ticketType ? $user->ticketType->name : 'NULL') . "\n";
    
    if ($user->ticketType) {
        $raceCategory = \App\Models\RaceCategory::find($user->ticketType->race_category_id);
        echo "Ticket Type Race Category: " . ($raceCategory ? $raceCategory->name : 'NULL') . "\n";
    }
    
    echo "Payment Confirmed: " . ($user->payment_confirmed ? 'YES' : 'NO') . "\n";
    echo "Registration Date: " . $user->created_at . "\n\n";
}

echo "=== VERIFICATION OF COUNTS ===\n\n";

// Dashboard calculation (exclude admins)
$dashboardPaidCount = \App\Models\User::where('payment_confirmed', true)
    ->where('role', '!=', 'admin')
    ->count();
echo "Dashboard Paid Count (excluding admins): " . $dashboardPaidCount . "\n";

// Manual category stats calculation
$categoryStatsPaidCount = 0;

echo "\nCategory breakdown:\n";

// 5K categories
$count5KEarlyBird = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '5K')
    ->where('ticket_type_id', 1) // Early Bird
    ->where('role', '!=', 'admin')
    ->count();
echo "5K Early Bird: " . $count5KEarlyBird . "\n";
$categoryStatsPaidCount += $count5KEarlyBird;

$count5KRegular = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '5K')
    ->where('ticket_type_id', 2) // Regular
    ->where('role', '!=', 'admin')
    ->count();
echo "5K Regular: " . $count5KRegular . "\n";
$categoryStatsPaidCount += $count5KRegular;

$count5KKolektif = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '5K')
    ->where('ticket_type_id', 3) // Kolektif
    ->where('role', '!=', 'admin')
    ->count();
echo "5K Kolektif: " . $count5KKolektif . "\n";
$categoryStatsPaidCount += $count5KKolektif;

$count5KWakaf = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '5K')
    ->where('ticket_type_id', 12) // Wakaf
    ->where('role', '!=', 'admin')
    ->count();
echo "5K Wakaf: " . $count5KWakaf . "\n";
$categoryStatsPaidCount += $count5KWakaf;

// 10K categories
$count10KEarlyBird = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '10K')
    ->whereIn('ticket_type_id', [4, 5]) // Early Bird, Regular for 10K
    ->where('role', '!=', 'admin')
    ->count();

$count10KEarlyBirdOnly = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '10K')
    ->where('ticket_type_id', 4) // Early Bird
    ->where('role', '!=', 'admin')
    ->count();
echo "10K Early Bird: " . $count10KEarlyBirdOnly . "\n";
$categoryStatsPaidCount += $count10KEarlyBirdOnly;

$count10KRegular = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '10K')
    ->where('ticket_type_id', 5) // Regular
    ->where('role', '!=', 'admin')
    ->count();
echo "10K Regular: " . $count10KRegular . "\n";
$categoryStatsPaidCount += $count10KRegular;

$count10KKolektif = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '10K')
    ->where('ticket_type_id', 6) // Kolektif
    ->where('role', '!=', 'admin')
    ->count();
echo "10K Kolektif: " . $count10KKolektif . "\n";
$categoryStatsPaidCount += $count10KKolektif;

// 21K categories
$count21KEarlyBird = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '21K')
    ->where('ticket_type_id', 7) // Early Bird
    ->where('role', '!=', 'admin')
    ->count();
echo "21K Early Bird: " . $count21KEarlyBird . "\n";
$categoryStatsPaidCount += $count21KEarlyBird;

$count21KRegular = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '21K')
    ->where('ticket_type_id', 8) // Regular
    ->where('role', '!=', 'admin')
    ->count();
echo "21K Regular: " . $count21KRegular . "\n";
$categoryStatsPaidCount += $count21KRegular;

$count21KKolektif = \App\Models\User::where('payment_confirmed', true)
    ->where('race_category', '21K')
    ->where('ticket_type_id', 25) // Kolektif 21K
    ->where('role', '!=', 'admin')
    ->count();
echo "21K Kolektif: " . $count21KKolektif . "\n";
$categoryStatsPaidCount += $count21KKolektif;

echo "\nTotal Category Stats Paid Count: " . $categoryStatsPaidCount . "\n";
echo "Dashboard Paid Count: " . $dashboardPaidCount . "\n";
echo "Difference: " . ($dashboardPaidCount - $categoryStatsPaidCount) . "\n\n";

// Check if problematic user is included in category stats
echo "=== CHECKING PROBLEMATIC USER ===\n";
echo "User 2109 has race_category '10K' but ticket_type_id 25 (21K Kolektif)\n";
echo "This user is counted in Dashboard (payment_confirmed=true)\n";
echo "But NOT counted in Category Stats because:\n";
echo "- Category Stats looks for race_category='21K' + ticket_type_id=25\n";
echo "- But user has race_category='10K' + ticket_type_id=25\n";
echo "- So it doesn't match any category+ticket combination\n\n";

echo "=== RECOMMENDED SOLUTION ===\n";
echo "Fix the user's data to be consistent:\n";
echo "UPDATE users SET race_category = '21K' WHERE id = 2109;\n";
echo "OR\n";
echo "UPDATE users SET ticket_type_id = 6 WHERE id = 2109; -- 6 is 10K Kolektif\n";

echo "\n=== DISCREPANCY ANALYSIS COMPLETE ===\n";
