<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\TicketType;

echo "=== ALL TICKET TYPES ===\n";
$tickets = TicketType::with('raceCategory')->get();
foreach($tickets as $ticket) {
    echo "ID: {$ticket->id}, Name: {$ticket->name}, Price: Rp " . number_format($ticket->price) . ", Race Category: " . ($ticket->raceCategory->name ?? 'N/A') . "\n";
}

echo "\n=== COLLECTIVE TICKET TYPES ===\n";
$collectiveTickets = TicketType::with('raceCategory')
    ->where('name', 'LIKE', '%kolektif%')
    ->orWhere('name', 'LIKE', '%collective%')
    ->get();
    
foreach($collectiveTickets as $ticket) {
    echo "ID: {$ticket->id}, Name: {$ticket->name}, Price: Rp " . number_format($ticket->price) . ", Race Category ID: {$ticket->race_category_id}, Race Category: " . ($ticket->raceCategory->name ?? 'N/A') . "\n";
}
