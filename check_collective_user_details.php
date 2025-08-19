<?php
/**
 * Check recent collective import details
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING RECENT COLLECTIVE IMPORT DETAILS ===\n\n";

// Get recent collective import user
$user = \App\Models\User::where('xendit_external_id', 'LIKE', 'AMAZING-ADMIN-COLLECTIVE-%')
    ->orderBy('created_at', 'desc')
    ->first();

if ($user) {
    echo "🔍 Recent Collective Import User:\n";
    echo "   ID: {$user->id}\n";
    echo "   Name: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Race Category: '" . ($user->race_category ?: 'NULL') . "'\n";
    echo "   Jersey Size: '" . ($user->jersey_size ?: 'NULL') . "'\n";
    echo "   Blood Type: '" . ($user->blood_type ?: 'NULL') . "'\n";
    echo "   External ID: {$user->xendit_external_id}\n";
    echo "   Created: {$user->created_at}\n";
    
    // Check if all required fields are present
    echo "\n📋 Field Validation:\n";
    $requiredFields = [
        'race_category' => $user->race_category,
        'jersey_size' => $user->jersey_size,
        'blood_type' => $user->blood_type,
        'whatsapp_number' => $user->whatsapp_number,
        'birth_place' => $user->birth_place,
        'birth_date' => $user->birth_date,
        'address' => $user->address,
        'regency_name' => $user->regency_name,
        'province_name' => $user->province_name,
        'emergency_contact_name' => $user->emergency_contact_name,
        'emergency_contact_phone' => $user->emergency_contact_phone,
    ];
    
    foreach ($requiredFields as $field => $value) {
        $status = $value ? "✅ '{$value}'" : "❌ NULL/Empty";
        echo "   {$field}: {$status}\n";
    }
    
    // Check relationship with RaceCategory
    echo "\n🔗 Race Category Relationship:\n";
    $raceCategory = $user->raceCategory;
    if ($raceCategory) {
        echo "   ✅ Found relationship: {$raceCategory->name} (ID: {$raceCategory->id})\n";
        echo "   Price: Rp " . number_format($raceCategory->price, 0, ',', '.') . "\n";
    } else {
        echo "   ❌ No relationship found for race_category: '{$user->race_category}'\n";
        
        // Check if race category exists in database
        $existingCategory = \App\Models\RaceCategory::where('name', $user->race_category)->first();
        if ($existingCategory) {
            echo "   💡 Category exists in database: {$existingCategory->name}\n";
        } else {
            echo "   ⚠️  Category NOT found in database!\n";
        }
    }
    
} else {
    echo "❌ No collective import users found\n";
}

echo "\n=== CHECK COMPLETE ===\n";
