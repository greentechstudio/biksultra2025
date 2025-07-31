<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Backfill xendit_external_id for Existing Users ===\n\n";

// Find users without xendit_external_id
$usersWithoutId = App\Models\User::whereNull('xendit_external_id')->get();

echo "1. Found {$usersWithoutId->count()} users without xendit_external_id\n\n";

if ($usersWithoutId->count() === 0) {
    echo "✅ All users already have xendit_external_id!\n";
    exit;
}

echo "2. Preview of users to be updated:\n";
foreach ($usersWithoutId->take(5) as $user) {
    $proposedId = 'AMAZING-REG-' . $user->id . '-' . time();
    echo "   ID: {$user->id} | {$user->name} | {$user->email} → {$proposedId}\n";
}

if ($usersWithoutId->count() > 5) {
    $remaining = $usersWithoutId->count() - 5;
    echo "   ... and {$remaining} more users\n";
}

echo "\n3. Do you want to proceed with backfill? (y/N): ";
$confirmation = trim(fgets(STDIN));

if (strtolower($confirmation) !== 'y') {
    echo "❌ Backfill cancelled by user\n";
    exit;
}

echo "\n4. Starting backfill process...\n";

$updated = 0;
$failed = 0;

foreach ($usersWithoutId as $user) {
    try {
        $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
        
        $result = $user->update(['xendit_external_id' => $externalId]);
        
        if ($result) {
            $updated++;
            echo "   ✅ Updated User {$user->id} ({$user->name}): {$externalId}\n";
        } else {
            $failed++;
            echo "   ❌ Failed to update User {$user->id} ({$user->name})\n";
        }
        
        // Small delay to ensure unique timestamps
        usleep(10000); // 0.01 second
        
    } catch (Exception $e) {
        $failed++;
        echo "   ❌ Exception updating User {$user->id} ({$user->name}): " . $e->getMessage() . "\n";
    }
}

echo "\n5. Backfill complete!\n";
echo "   ✅ Successfully updated: {$updated} users\n";
echo "   ❌ Failed to update: {$failed} users\n";

// Verify the results
echo "\n6. Verification:\n";
$totalUsers = App\Models\User::count();
$usersWithId = App\Models\User::whereNotNull('xendit_external_id')->count();
$coverage = round(($usersWithId / $totalUsers) * 100, 2);

echo "   Total users: {$totalUsers}\n";
echo "   Users with xendit_external_id: {$usersWithId}\n";
echo "   Coverage: {$coverage}%\n";

if ($coverage >= 100) {
    echo "   🎉 All users now have xendit_external_id!\n";
} else {
    $remaining = $totalUsers - $usersWithId;
    echo "   ⚠️  {$remaining} users still missing xendit_external_id\n";
}

echo "\n=== Backfill Process Complete ===\n";

?>
