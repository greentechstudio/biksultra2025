<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RE-ENABLING WAKAF FEATURE ===\n\n";

// Re-enable wakaf ticket type (set is_active = true)
$wakafTicketType = \App\Models\TicketType::where('name', 'wakaf')->first();

if ($wakafTicketType) {
    $wasActive = $wakafTicketType->is_active;
    $wakafTicketType->is_active = true;
    $wakafTicketType->save();
    
    echo "✅ WAKAF TICKET TYPE STATUS UPDATED:\n";
    echo "ID: " . $wakafTicketType->id . "\n";
    echo "Name: " . $wakafTicketType->name . "\n";
    echo "Price: Rp " . number_format($wakafTicketType->price, 0, ',', '.') . "\n";
    echo "Status: " . ($wasActive ? 'Already ACTIVE' : 'INACTIVE → ACTIVE') . "\n";
    echo "Race Category: " . ($wakafTicketType->raceCategory ? $wakafTicketType->raceCategory->name : 'None') . "\n";
    echo "Registered Count: " . $wakafTicketType->registered_count . "\n";
    echo "\n";
    
    echo "📋 MANUAL STEPS REQUIRED TO FULLY RE-ENABLE WAKAF:\n\n";
    echo "1. UNCOMMENT ROUTES in routes/web.php:\n";
    echo "   - Remove // from wakaf routes (lines around 74-76)\n\n";
    
    echo "2. UNCOMMENT CONTROLLER METHODS in app/Http/Controllers/AuthController.php:\n";
    echo "   - Remove /* */ comments from showWakafRegister() method\n";
    echo "   - Remove /* */ comments from registerWakaf() method\n";
    echo "   - Remove /* */ comments from wakafSuccess() method\n\n";
    
    echo "3. UNCOMMENT BUTTON in resources/views/partials/landing-registration.blade.php:\n";
    echo "   - Remove {{-- --}} comments from wakaf register button\n\n";
    
    echo "4. RESTORE VIEW FILES (if needed):\n";
    echo "   - register-wakaf.blade.php.backup → register-wakaf.blade.php\n";
    echo "   - wakaf-success.blade.php.backup → wakaf-success.blade.php\n\n";
    
    echo "5. CLEAR CACHE:\n";
    echo "   - php artisan route:clear\n";
    echo "   - php artisan view:clear\n";
    echo "   - php artisan config:clear\n\n";
    
} else {
    echo "❌ NO WAKAF TICKET TYPE FOUND!\n";
    echo "Cannot re-enable wakaf feature.\n\n";
}

echo "=== WAKAF TICKET TYPE RE-ENABLED (Manual steps required for full activation) ===\n";
