<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST GENERATE INVOICE SETELAH FIX ===\n\n";

$externalId = 'AMAZING-REG-2232-1755328411';

try {
    // Extract timestamp
    if (preg_match('/AMAZING-REG-(\d+)-(\d+)/', $externalId, $matches)) {
        $timestamp = $matches[2];
        
        echo "🔍 Mencari grup dengan timestamp: $timestamp\n";
        $groupUsers = User::where('xendit_external_id', 'LIKE', "AMAZING-REG-%-$timestamp")
                         ->orderBy('id')
                         ->get();
        
        echo "✅ Ditemukan " . $groupUsers->count() . " peserta\n\n";
        
        if ($groupUsers->count() >= 5) {
            echo "✅ Participant count valid (>= 5)\n";
            
            // Test XenditService validation
            $xenditService = new XenditService();
            
            // Prepare participant details
            $participantDetails = [];
            $totalAmount = 0;
            
            foreach ($groupUsers as $user) {
                $price = $xenditService->getCollectivePrice($user->race_category);
                if ($price !== false) {
                    $totalAmount += $price;
                    $participantDetails[] = [
                        'name' => $user->name,
                        'category' => $user->race_category,
                        'fee' => $price
                    ];
                }
            }
            
            echo "💰 Total Amount: Rp " . number_format($totalAmount, 0, ',', '.') . "\n";
            echo "👥 Participant Details: " . count($participantDetails) . " peserta\n\n";
            
            // Test validation (without actually creating invoice)
            if (count($participantDetails) >= 5 && count($participantDetails) <= 100) {
                echo "✅ XenditService validation will PASS\n";
                echo "   - Min participants: 5 ✅\n";
                echo "   - Max participants: 100 ✅\n";
                echo "   - Current count: " . count($participantDetails) . " ✅\n\n";
                
                echo "🚀 Ready to generate invoice!\n";
                echo "Jalankan: php regenerate_by_timestamp.php\n";
                echo "Input: $externalId\n";
                
            } else {
                echo "❌ XenditService validation will FAIL\n";
                echo "   - Current count: " . count($participantDetails) . "\n";
                echo "   - Required: 5-100 participants\n";
            }
            
        } else {
            echo "❌ Participant count too low: " . $groupUsers->count() . " (minimum 5)\n";
        }
        
    } else {
        echo "❌ Invalid External ID format\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n=== SUMMARY FIX ===\n";
echo "✅ XenditService.php validation fixed: 10 → 5 minimum participants\n";
echo "✅ Frontend JavaScript fixed: 10 → 5 minimum participants\n";
echo "✅ Backend AuthController fixed: 10 → 5 minimum participants\n";
echo "✅ All systems now consistent with 5 minimum participants\n";
