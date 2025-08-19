<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the race category normalization function
function normalizeRaceCategory($category, $availableCategories)
{
    $category = trim($category);
    
    // Direct match first (case insensitive)
    foreach ($availableCategories as $validCategory) {
        if (strcasecmp($category, $validCategory) === 0) {
            return $validCategory;
        }
    }
    
    // Special mappings for common variations
    $categoryMappings = [
        '21K' => 'HM 21K',
        '21k' => 'HM 21K',
        'Half Marathon' => 'HM 21K',
        'Half' => 'HM 21K',
        'HM' => 'HM 21K',
        '42K' => 'FM 42K',  // Future mapping if needed
        '42k' => 'FM 42K',
        'Full Marathon' => 'FM 42K',
        'Full' => 'FM 42K',
        'FM' => 'FM 42K',
    ];
    
    if (isset($categoryMappings[$category])) {
        // Check if the mapped category exists in available categories
        $mappedCategory = $categoryMappings[$category];
        foreach ($availableCategories as $validCategory) {
            if (strcasecmp($mappedCategory, $validCategory) === 0) {
                return $validCategory;
            }
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

echo "=== RACE CATEGORY NORMALIZATION TEST ===\n\n";

$availableCategories = \App\Models\RaceCategory::where('active', 1)->pluck('name')->toArray();
echo "Available categories: " . implode(', ', $availableCategories) . "\n\n";

$testInputs = ['5K', '10K', '21K', '42K', 'HM 21K', 'hm 21k', 'Half Marathon', 'Full Marathon'];

foreach ($testInputs as $input) {
    $normalized = normalizeRaceCategory($input, $availableCategories);
    $status = $normalized ? "✅ VALID" : "❌ INVALID";
    
    printf("%-15s -> %-10s %s\n", 
        "'{$input}'", 
        $normalized ? $normalized : 'null',
        $status
    );
}

echo "\n=== RESULT ===\n";
echo "Race category normalization now supports:\n";
echo "• Direct matches: 5K, 10K, HM 21K\n";
echo "• Common variations: 21K -> HM 21K, Half Marathon -> HM 21K\n";
echo "• Case insensitive matching\n";
