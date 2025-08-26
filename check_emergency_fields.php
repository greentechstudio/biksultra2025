<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING EMERGENCY CONTACT FIELDS ===\n\n";

// Check users table structure for emergency fields
$columns = \Schema::getColumnListing('users');
echo "Emergency-related columns in users table:\n";
foreach($columns as $column) {
    if (strpos($column, 'emergency') !== false) {
        echo "- " . $column . "\n";
    }
}

echo "\n=== SAMPLE USER DATA ===\n";
$user = \App\Models\User::where('role', '!=', 'admin')->first();
if ($user) {
    echo "Sample user emergency fields:\n";
    echo "emergency_contact_name: '" . $user->emergency_contact_name . "'\n";
    echo "emergency_contact_phone: '" . $user->emergency_contact_phone . "'\n";
    
    // Check if there are alternative field names
    $attributes = $user->getAttributes();
    foreach($attributes as $key => $value) {
        if (strpos($key, 'emergency') !== false) {
            echo $key . ": '" . $value . "'\n";
        }
    }
} else {
    echo "No users found\n";
}

echo "\n=== CURRENT EXPORT CSV FIELDS ===\n";
echo "In export method, using:\n";
echo "- \$user->emergency_contact\n";
echo "- \$user->emergency_phone\n";

echo "\n=== RECOMMENDATION ===\n";
echo "If export shows empty emergency data, the field names might be:\n";
echo "- emergency_contact_name (instead of emergency_contact)\n";
echo "- emergency_contact_phone (instead of emergency_phone)\n";
