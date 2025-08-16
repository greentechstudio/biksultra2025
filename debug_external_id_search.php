<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG EXTERNAL ID SEARCH ===\n\n";

$externalId = 'AMAZING-REG-1570-1754580207';

echo "🔍 Mencari External ID: $externalId\n\n";

// 1. Exact match
echo "1. EXACT MATCH SEARCH:\n";
$exactUsers = User::where('xendit_external_id', $externalId)->get();
echo "   Query: WHERE xendit_external_id = '$externalId'\n";
echo "   Result: " . $exactUsers->count() . " users found\n";

if ($exactUsers->count() > 0) {
    foreach ($exactUsers as $user) {
        echo "   - ID: {$user->id}, Name: {$user->name}, External ID: {$user->xendit_external_id}\n";
    }
}

// 2. LIKE pattern search
echo "\n2. LIKE PATTERN SEARCH:\n";
$likeUsers = User::where('xendit_external_id', 'LIKE', "$externalId%")->get();
echo "   Query: WHERE xendit_external_id LIKE '$externalId%'\n";
echo "   Result: " . $likeUsers->count() . " users found\n";

if ($likeUsers->count() > 0) {
    foreach ($likeUsers as $user) {
        echo "   - ID: {$user->id}, Name: {$user->name}, External ID: {$user->xendit_external_id}\n";
    }
}

// 3. Search similar patterns
echo "\n3. SIMILAR PATTERN SEARCH:\n";
$similarUsers = User::where('xendit_external_id', 'LIKE', 'AMAZING-REG-1570-%')->get();
echo "   Query: WHERE xendit_external_id LIKE 'AMAZING-REG-1570-%'\n";
echo "   Result: " . $similarUsers->count() . " users found\n";

if ($similarUsers->count() > 0) {
    $uniqueExternalIds = $similarUsers->pluck('xendit_external_id')->unique();
    echo "   Unique External IDs found:\n";
    foreach ($uniqueExternalIds as $extId) {
        $count = $similarUsers->where('xendit_external_id', $extId)->count();
        echo "   - $extId ($count users)\n";
    }
}

// 4. Check database connection and total users
echo "\n4. DATABASE INFO:\n";
$totalUsers = User::count();
echo "   Total users in database: $totalUsers\n";

$recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
echo "   Recent 5 users:\n";
foreach ($recentUsers as $user) {
    echo "   - ID: {$user->id}, External ID: {$user->xendit_external_id}\n";
}

// 5. Check if data exists with different case or spaces
echo "\n5. CASE-INSENSITIVE SEARCH:\n";
$caseInsensitive = User::whereRaw("LOWER(xendit_external_id) = ?", [strtolower($externalId)])->get();
echo "   Query: WHERE LOWER(xendit_external_id) = '" . strtolower($externalId) . "'\n";
echo "   Result: " . $caseInsensitive->count() . " users found\n";

echo "\n=== SUMMARY ===\n";
echo "Target External ID: $externalId\n";
echo "Exact match: " . $exactUsers->count() . " users\n";
echo "Like pattern: " . $likeUsers->count() . " users\n";
echo "Similar pattern: " . $similarUsers->count() . " users\n";
echo "Case insensitive: " . $caseInsensitive->count() . " users\n";

if ($exactUsers->count() == 0) {
    echo "\n💡 KEMUNGKINAN MASALAH:\n";
    echo "1. External ID tidak ada di database\n";
    echo "2. Ada perbedaan case (huruf besar/kecil)\n";
    echo "3. Ada spasi atau karakter tersembunyi\n";
    echo "4. Data ada di server production, bukan localhost\n";
    echo "5. Typo dalam External ID\n";
}
