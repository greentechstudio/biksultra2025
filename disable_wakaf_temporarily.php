<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEMPORARILY DISABLING WAKAF FEATURE ===\n\n";

// Disable wakaf ticket type (set is_active = false)
$wakafTicketType = \App\Models\TicketType::where('name', 'wakaf')->first();

if ($wakafTicketType) {
    $wasActive = $wakafTicketType->is_active;
    $wakafTicketType->is_active = false;
    $wakafTicketType->save();
    
    echo "✅ WAKAF TICKET TYPE STATUS UPDATED:\n";
    echo "ID: " . $wakafTicketType->id . "\n";
    echo "Name: " . $wakafTicketType->name . "\n";
    echo "Price: Rp " . number_format($wakafTicketType->price, 0, ',', '.') . "\n";
    echo "Status: " . ($wasActive ? 'ACTIVE → INACTIVE' : 'Already INACTIVE') . "\n";
    echo "Race Category: " . ($wakafTicketType->raceCategory ? $wakafTicketType->raceCategory->name : 'None') . "\n";
    echo "Registered Count: " . $wakafTicketType->registered_count . "\n";
    echo "\n";
    
    if ($wakafTicketType->registered_count > 0) {
        echo "⚠️  WARNING: There are " . $wakafTicketType->registered_count . " users already registered with wakaf ticket type.\n";
        echo "   These registrations are still valid and preserved in the database.\n\n";
    }
    
    echo "📋 NOTES:\n";
    echo "- Wakaf feature has been temporarily disabled\n";
    echo "- Routes are commented out in routes/web.php\n";
    echo "- Controller methods are commented out in AuthController.php\n";
    echo "- Button removed from landing page\n";
    echo "- View files backed up with .backup extension\n";
    echo "- To re-enable: uncomment routes, methods, button, and run enable_wakaf_temporarily.php\n\n";
    
} else {
    echo "❌ NO WAKAF TICKET TYPE FOUND!\n";
    echo "Nothing to disable.\n\n";
}

echo "=== WAKAF FEATURE TEMPORARILY DISABLED ===\n";
