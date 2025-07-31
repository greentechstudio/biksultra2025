<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Analyzing xendit_external_id Distribution ===\n\n";

// Check distribution of xendit_external_id
echo "1. xendit_external_id statistics:\n";
$totalUsers = App\Models\User::count();
$usersWithExternalId = App\Models\User::whereNotNull('xendit_external_id')->count();
$usersWithoutExternalId = App\Models\User::whereNull('xendit_external_id')->count();

echo "   Total users: {$totalUsers}\n";
echo "   Users with xendit_external_id: {$usersWithExternalId}\n";
echo "   Users without xendit_external_id: {$usersWithoutExternalId}\n";

if ($totalUsers > 0) {
    $percentage = round(($usersWithExternalId / $totalUsers) * 100, 2);
    echo "   Coverage: {$percentage}%\n";
}

echo "\n2. Recent users with xendit_external_id:\n";
$recentWithId = App\Models\User::whereNotNull('xendit_external_id')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'name', 'xendit_external_id', 'created_at']);

foreach ($recentWithId as $user) {
    echo "   ID: {$user->id} | {$user->name} | {$user->xendit_external_id} | {$user->created_at}\n";
}

echo "\n3. Users without xendit_external_id (oldest first):\n";
$withoutId = App\Models\User::whereNull('xendit_external_id')
    ->orderBy('id', 'asc')
    ->limit(5)
    ->get(['id', 'name', 'email', 'created_at']);

if ($withoutId->count() > 0) {
    foreach ($withoutId as $user) {
        echo "   ID: {$user->id} | {$user->name} | {$user->email} | {$user->created_at}\n";
    }
} else {
    echo "   ✅ All users have xendit_external_id!\n";
}

echo "\n4. Today's registrations:\n";
$today = date('Y-m-d');
$todayUsers = App\Models\User::whereDate('created_at', $today)
    ->orderBy('id', 'desc')
    ->get(['id', 'name', 'xendit_external_id', 'created_at']);

echo "   Total registrations today: " . $todayUsers->count() . "\n";
foreach ($todayUsers as $user) {
    $externalId = $user->xendit_external_id ?? 'NULL';
    echo "   ID: {$user->id} | {$user->name} | External ID: {$externalId} | {$user->created_at}\n";
}

echo "\n5. Fix recommendation:\n";
if ($usersWithoutExternalId > 0) {
    echo "   ⚠️  Found {$usersWithoutExternalId} users without xendit_external_id\n";
    echo "   💡 These are likely users registered before the fix was implemented\n";
    echo "   🔧 Would you like to backfill xendit_external_id for existing users?\n";
    
    echo "\n   Backfill script would update users like this:\n";
    $sampleUsers = App\Models\User::whereNull('xendit_external_id')->limit(3)->get(['id', 'name']);
    foreach ($sampleUsers as $user) {
        $proposedId = 'AMAZING-REG-' . $user->id . '-' . time();
        echo "      User {$user->id} ({$user->name}) → {$proposedId}\n";
    }
} else {
    echo "   ✅ All users have xendit_external_id - no action needed!\n";
}

echo "\n=== Analysis Complete ===\n";

?>
