<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Race Categories ===\n";
$raceCategories = \App\Models\RaceCategory::all(['id', 'name']);
foreach ($raceCategories as $category) {
    echo "{$category->id}: {$category->name}\n";
}

echo "\n=== Ticket Types ===\n";
$ticketTypes = \App\Models\TicketType::with('raceCategory')->get(['id', 'name', 'price', 'race_category_id']);
foreach ($ticketTypes as $ticket) {
    $categoryName = $ticket->raceCategory ? $ticket->raceCategory->name : 'null';
    echo "{$ticket->id}: {$ticket->name} - {$ticket->price} (category: {$categoryName})\n";
}

echo "\n=== Wakaf Ticket Type Detail ===\n";
$wakafTicket = \App\Models\TicketType::where('name', 'wakaf')->with('raceCategory')->first();
if ($wakafTicket) {
    echo "Wakaf Ticket:\n";
    echo "- ID: {$wakafTicket->id}\n";
    echo "- Name: {$wakafTicket->name}\n";
    echo "- Price: {$wakafTicket->price}\n";
    echo "- Race Category ID: {$wakafTicket->race_category_id}\n";
    echo "- Race Category Name: " . ($wakafTicket->raceCategory ? $wakafTicket->raceCategory->name : 'null') . "\n";
    echo "- Is Active: " . ($wakafTicket->is_active ? 'Yes' : 'No') . "\n";
} else {
    echo "Wakaf ticket type not found!\n";
}

echo "\n=== Test getCurrentTicketType for 5K ===\n";
$ticket5K = \App\Models\TicketType::getCurrentTicketType('5K');
if ($ticket5K) {
    echo "5K Current Ticket:\n";
    echo "- ID: {$ticket5K->id}\n";
    echo "- Name: {$ticket5K->name}\n";
    echo "- Price: {$ticket5K->price}\n";
} else {
    echo "No current ticket type found for 5K\n";
}

echo "\n=== Test getCurrentTicketType for wakaf ===\n";
$ticketWakaf = \App\Models\TicketType::getCurrentTicketType('wakaf');
if ($ticketWakaf) {
    echo "Wakaf Current Ticket:\n";
    echo "- ID: {$ticketWakaf->id}\n";
    echo "- Name: {$ticketWakaf->name}\n";
    echo "- Price: {$ticketWakaf->price}\n";
} else {
    echo "No current ticket type found for 'wakaf' race category\n";
}
