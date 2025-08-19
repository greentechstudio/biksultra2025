<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== RACE CATEGORIES ===\n";
$categories = App\Models\RaceCategory::all();
foreach($categories as $cat) {
    echo "ID: {$cat->id} | Name: '{$cat->name}' | Active: " . ($cat->active ? 'Yes' : 'No') . "\n";
}

echo "\n=== TICKET TYPES WITH CATEGORIES ===\n";
$tickets = App\Models\TicketType::with('raceCategory')->get();
foreach($tickets as $ticket) {
    $categoryName = $ticket->raceCategory ? $ticket->raceCategory->name : 'N/A';
    echo "Ticket: '{$ticket->name}' | Category: '{$categoryName}' | Active: " . ($ticket->is_active ? 'Yes' : 'No') . "\n";
}
