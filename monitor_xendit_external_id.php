<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== xendit_external_id Monitoring Dashboard ===\n\n";

// Overall statistics
$totalUsers = App\Models\User::count();
$usersWithId = App\Models\User::whereNotNull('xendit_external_id')->count();
$usersWithoutId = $totalUsers - $usersWithId;
$coverage = round(($usersWithId / $totalUsers) * 100, 2);

echo "1. Overall Statistics:\n";
echo "   Total users: {$totalUsers}\n";
echo "   Users with xendit_external_id: {$usersWithId}\n";
echo "   Users without xendit_external_id: {$usersWithoutId}\n";
echo "   Coverage: {$coverage}%\n\n";

// Recent registrations (last 24 hours)
$yesterday = now()->subDay();
$recentUsers = App\Models\User::where('created_at', '>=', $yesterday)
    ->orderBy('created_at', 'desc')
    ->get();

echo "2. Recent Registrations (Last 24 hours):\n";
if ($recentUsers->count() === 0) {
    echo "   No registrations in the last 24 hours\n\n";
} else {
    echo "   Found {$recentUsers->count()} recent registration(s):\n";
    foreach ($recentUsers as $user) {
        $hasId = $user->xendit_external_id ? '✅' : '❌';
        $idValue = $user->xendit_external_id ?: 'NULL';
        $timeAgo = $user->created_at->diffForHumans();
        echo "     {$hasId} {$user->name} | {$user->email} | {$timeAgo} | ID: {$idValue}\n";
    }
    echo "\n";
}

// Check for any null xendit_external_id
echo "3. Users Missing xendit_external_id:\n";
$missingUsers = App\Models\User::whereNull('xendit_external_id')->get();
if ($missingUsers->count() === 0) {
    echo "   ✅ All users have xendit_external_id!\n\n";
} else {
    echo "   ⚠️  Found {$missingUsers->count()} users without xendit_external_id:\n";
    foreach ($missingUsers->take(10) as $user) {
        $createdAt = $user->created_at->format('Y-m-d H:i:s');
        echo "     ID: {$user->id} | {$user->name} | {$user->email} | Created: {$createdAt}\n";
    }
    if ($missingUsers->count() > 10) {
        $remaining = $missingUsers->count() - 10;
        echo "     ... and {$remaining} more\n";
    }
    echo "\n";
}

// Pattern analysis
echo "4. xendit_external_id Pattern Analysis:\n";
$patterned = App\Models\User::where('xendit_external_id', 'LIKE', 'AMAZING-REG-%')->count();
$nonPatterned = App\Models\User::whereNotNull('xendit_external_id')
    ->where('xendit_external_id', 'NOT LIKE', 'AMAZING-REG-%')->count();

echo "   Correct pattern (AMAZING-REG-*): {$patterned}\n";
echo "   Incorrect pattern: {$nonPatterned}\n";

if ($nonPatterned > 0) {
    echo "   ⚠️  Some users have non-standard xendit_external_id format\n";
    $incorrect = App\Models\User::whereNotNull('xendit_external_id')
        ->where('xendit_external_id', 'NOT LIKE', 'AMAZING-REG-%')
        ->take(5)
        ->get();
    foreach ($incorrect as $user) {
        echo "     ID: {$user->id} | Pattern: {$user->xendit_external_id}\n";
    }
}
echo "\n";

// Registration method detection (last 10 users)
echo "5. Recent Registration Analysis (Last 10 users):\n";
$lastTen = App\Models\User::orderBy('created_at', 'desc')->take(10)->get();
foreach ($lastTen as $user) {
    $hasId = $user->xendit_external_id ? '✅' : '❌';
    $timeAgo = $user->created_at->diffForHumans();
    
    // Try to detect registration type based on various factors
    $registrationType = 'Unknown';
    if ($user->password_change_required ?? false) {
        $registrationType = 'Random Password/Collective';
    } elseif (strlen($user->password ?? '') < 20) {
        $registrationType = 'Regular/API';
    }
    
    echo "   {$hasId} {$user->name} | {$timeAgo} | Type: {$registrationType}\n";
}

echo "\n=== Monitoring Complete ===\n";
echo "🔍 Run this script regularly to monitor xendit_external_id generation\n";
echo "📊 Consider setting up automated alerts if coverage drops below 100%\n";

?>
