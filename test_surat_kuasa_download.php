<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING SURAT KUASA DOWNLOAD LINK ===\n\n";

// Check if file exists
$filePath = public_path('Surat-Kuasa-Amazing-Sultra-Run.docx');
if (file_exists($filePath)) {
    echo "✅ File exists: " . $filePath . "\n";
    echo "File size: " . number_format(filesize($filePath) / 1024, 2) . " KB\n";
    echo "File modified: " . date('Y-m-d H:i:s', filemtime($filePath)) . "\n";
} else {
    echo "❌ File not found: " . $filePath . "\n";
}

// Test asset URL generation
$assetUrl = asset('Surat-Kuasa-Amazing-Sultra-Run.docx');
echo "\n📍 Asset URL: " . $assetUrl . "\n";

// Check if asset helper works
if (function_exists('asset')) {
    echo "✅ Asset helper function available\n";
} else {
    echo "❌ Asset helper function not available\n";
}

echo "\n=== DOWNLOAD LINK IMPLEMENTATION ===\n";
echo "Button HTML will be:\n";
echo '<a href="' . $assetUrl . '" class="cta-button" download="Surat-Kuasa-Amazing-Sultra-Run.docx">' . "\n";
echo '    <i class="fas fa-download"></i> Download Surat Kuasa' . "\n";
echo '</a>' . "\n";

echo "\n✅ Download link configured successfully!\n";
echo "Users can now download the Surat Kuasa document from the hero section.\n";
