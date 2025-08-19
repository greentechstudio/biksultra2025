<?php
/**
 * Check race categories for CSV import
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING RACE CATEGORIES FOR CSV IMPORT ===\n\n";

// Check available race categories
$raceCategories = \App\Models\RaceCategory::where('active', 1)->get();

echo "📋 Available Race Categories:\n";
foreach ($raceCategories as $category) {
    echo "   - ID: {$category->id}, Name: '{$category->name}', Price: Rp " . number_format($category->price, 0, ',', '.') . "\n";
}

echo "\n📝 Race Category Names (for CSV validation):\n";
$categoryNames = $raceCategories->pluck('name')->toArray();
foreach ($categoryNames as $name) {
    echo "   - '{$name}'\n";
}

// Check recent CSV imports
echo "\n🔍 Checking recent CSV imports (last 20 users):\n";
$recentUsers = \App\Models\User::where('xendit_external_id', 'LIKE', 'AMAZING-ADMIN-%')
    ->orderBy('created_at', 'desc')
    ->limit(20)
    ->get(['id', 'name', 'race_category', 'xendit_external_id', 'created_at']);

if ($recentUsers->count() > 0) {
    foreach ($recentUsers as $user) {
        $category = $user->race_category ?: 'NULL';
        echo "   User {$user->id}: '{$user->name}' | Category: '{$category}' | External ID: {$user->xendit_external_id}\n";
    }
} else {
    echo "   No recent CSV imports found\n";
}

// Test normalization function
echo "\n🧪 Testing race category normalization:\n";
$testCategories = ['5K', '5k', '5 K', '10K', '10k', '21K', '21k', 'Invalid'];

// Copy normalization function from controller
function normalizeRaceCategory($category, $availableCategories)
{
    $category = trim($category);
    
    // Direct match first (case insensitive)
    foreach ($availableCategories as $validCategory) {
        if (strcasecmp($category, $validCategory) === 0) {
            return $validCategory;
        }
    }
    
    // Partial match for common abbreviations
    $category_lower = strtolower($category);
    foreach ($availableCategories as $validCategory) {
        $valid_lower = strtolower($validCategory);
        
        // Check if input is contained in valid category or vice versa
        if (strpos($valid_lower, $category_lower) !== false || 
            strpos($category_lower, $valid_lower) !== false) {
            return $validCategory;
        }
    }
    
    return null;
}

foreach ($testCategories as $testCat) {
    $normalized = normalizeRaceCategory($testCat, $categoryNames);
    $status = $normalized ? "✅ '{$normalized}'" : "❌ NULL";
    echo "   '{$testCat}' → {$status}\n";
}

echo "\n=== DIAGNOSIS COMPLETE ===\n";
