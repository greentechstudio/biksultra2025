<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING TABLE STRUCTURES ===\n\n";

try {
    // Check ticket_types table structure
    $columns = DB::select("DESCRIBE ticket_types");
    echo "📋 ticket_types table columns:\n";
    foreach ($columns as $column) {
        echo "   - {$column->Field} ({$column->Type})\n";
    }

    // Check if we have sample data
    $sampleTicket = DB::table('ticket_types')->first();
    if ($sampleTicket) {
        echo "\n📝 Sample ticket_type:\n";
        foreach ((array)$sampleTicket as $key => $value) {
            echo "   {$key}: {$value}\n";
        }
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
