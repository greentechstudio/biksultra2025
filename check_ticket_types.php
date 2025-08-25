<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ALL TICKET TYPES ===\n\n";

$tickets = \App\Models\TicketType::with('raceCategory')->get();
foreach($tickets as $t) {
    $raceCategoryName = $t->raceCategory ? $t->raceCategory->name : 'NULL';
    echo $t->id . ': ' . $t->name . ' (Race Category: ' . $raceCategoryName . ')' . "\n";
}

echo "\n=== USER COUNT PER TICKET TYPE (PAID) ===\n\n";

$totalPaid = 0;
foreach($tickets as $t) {
    $count = \App\Models\User::where('payment_confirmed', true)
        ->where('ticket_type_id', $t->id)
        ->where('role', '!=', 'admin')
        ->count();
    
    if ($count > 0) {
        $raceCategoryName = $t->raceCategory ? $t->raceCategory->name : 'NULL';
        echo "Ticket ID " . $t->id . " (" . $t->name . " - " . $raceCategoryName . "): " . $count . " users\n";
        $totalPaid += $count;
    }
}

echo "\nTotal Paid Users by Ticket Type: " . $totalPaid . "\n";
