<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING RACE CATEGORIES ===\n\n";

try {
    // Check if race_categories table exists
    $raceCategoriesColumns = DB::select("DESCRIBE race_categories");
    echo "📋 race_categories table columns:\n";
    foreach ($raceCategoriesColumns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }

    // Get all race categories
    $raceCategories = DB::table('race_categories')->get();
    echo "\n📝 Available Race Categories:\n";
    foreach ($raceCategories as $category) {
        echo "   - ID: {$category->id}, Name: {$category->name}\n";
    }

    // Get ticket types with race categories
    echo "\n🎫 Ticket Types with Categories:\n";
    $ticketTypes = DB::table('ticket_types')
        ->join('race_categories', 'ticket_types.race_category_id', '=', 'race_categories.id')
        ->select('ticket_types.name as ticket_name', 'race_categories.name as category_name', 'ticket_types.price')
        ->get();
    
    foreach ($ticketTypes as $ticket) {
        echo "   - {$ticket->category_name}: {$ticket->ticket_name} (Rp " . number_format($ticket->price) . ")\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
