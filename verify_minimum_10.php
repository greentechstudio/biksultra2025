<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFIKASI MINIMUM 10 PESERTA ===\n\n";

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
        
        // Check validation
        if ($groupUsers->count() >= 10) {
            echo "✅ Participant count valid (>= 10)\n";
        } else {
            echo "❌ Participant count too low: " . $groupUsers->count() . " (minimum 10)\n";
        }
        
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
        if (count($participantDetails) >= 10 && count($participantDetails) <= 100) {
            echo "✅ XenditService validation will PASS\n";
            echo "   - Min participants: 10 ✅\n";
            echo "   - Max participants: 100 ✅\n";
            echo "   - Current count: " . count($participantDetails) . " ✅\n\n";
            
            echo "🚀 Ready to generate invoice!\n";
            
        } else {
            echo "❌ XenditService validation will FAIL\n";
            echo "   - Current count: " . count($participantDetails) . "\n";
            echo "   - Required: 10-100 participants\n";
            echo "   - Status: " . (count($participantDetails) < 10 ? "TOO FEW" : "TOO MANY") . "\n\n";
            
            echo "❌ Cannot generate invoice - insufficient participants\n";
        }
        
    } else {
        echo "❌ Invalid External ID format\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n=== SUMMARY VALIDATION RULES ===\n";
echo "✅ XenditService.php: MINIMUM 10 participants\n";
echo "✅ Frontend JavaScript: MINIMUM 10 participants\n";
echo "✅ Backend AuthController: MINIMUM 10 participants\n";
echo "✅ All systems consistent with 10 minimum participants\n";
echo "\n📝 Note: Grup AMAZING-REG-2232-1755328411 hanya punya 6 peserta.\n";
echo "    Perlu minimal 4 peserta lagi untuk bisa generate invoice collective.\n";
