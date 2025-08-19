<?php
/**
 * Test script for gender normalization function
 * Usage: php test_gender_normalization.php
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Test data with various gender formats
$testGenders = [
    'Pria', 'pria', 'PRIA',
    'Laki-laki', 'laki-laki', 'LAKI-LAKI', 'laki',
    'L', 'l',
    'Male', 'male', 'MALE',
    'M', 'm',
    'Man', 'man', 'MAN',
    '1',
    
    'Wanita', 'wanita', 'WANITA',
    'Perempuan', 'perempuan', 'PEREMPUAN',
    'P', 'p',
    'Female', 'female', 'FEMALE',
    'F', 'f',
    'Woman', 'woman', 'WOMAN',
    '2',
    
    // Invalid cases
    'Invalid', 'X', 'Unknown', '3', ''
];

// Function to test (copied from CollectiveImportController)
function normalizeGender($gender)
{
    $gender = trim(strtolower($gender));
    
    // Map various gender formats to standard
    $genderMap = [
        // Indonesian - Male
        'pria' => 'Laki-laki',
        'laki-laki' => 'Laki-laki',
        'laki' => 'Laki-laki',
        'l' => 'Laki-laki',
        
        // Indonesian - Female  
        'wanita' => 'Perempuan',
        'perempuan' => 'Perempuan',
        'p' => 'Perempuan',
        
        // English - Male
        'male' => 'Laki-laki',
        'm' => 'Laki-laki',
        'man' => 'Laki-laki',
        
        // English - Female
        'female' => 'Perempuan',
        'f' => 'Perempuan',
        'woman' => 'Perempuan',
        
        // Numbers (sometimes used)
        '1' => 'Laki-laki',
        '2' => 'Perempuan',
    ];
    
    return $genderMap[$gender] ?? null;
}

echo "=== Gender Normalization Test ===\n\n";

foreach ($testGenders as $input) {
    $normalized = normalizeGender($input);
    $status = $normalized ? "✓ VALID" : "✗ INVALID";
    
    printf("%-15s -> %-8s %s\n", 
        "'{$input}'", 
        $normalized ? $normalized : 'null',
        $status
    );
}

echo "\n=== Summary ===\n";
echo "This test verifies that the gender normalization function:\n";
echo "1. Accepts various Indonesian gender formats (Pria, Laki-laki, L, etc.)\n";
echo "2. Accepts various English gender formats (Male, Female, M, F, etc.)\n";
echo "3. Accepts numeric codes (1=Pria, 2=Wanita)\n";
echo "4. Returns standardized 'Laki-laki' or 'Perempuan' values\n";
echo "5. Returns null for invalid inputs\n";
echo "\nAll valid inputs should now be accepted in CSV import!\n";
