<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== BACKFILL XENDIT INVOICES FOR EXISTING USERS ===\n\n";

try {
    $xenditService = new XenditService();
    
    // Find users without invoice IDs
    $usersWithoutInvoices = User::whereNull('xendit_invoice_id')
        ->whereNotNull('xendit_external_id')
        ->orderBy('created_at', 'asc')
        ->get();

    echo "1. Found {$usersWithoutInvoices->count()} users without invoices\n\n";

    if ($usersWithoutInvoices->count() === 0) {
        echo "✅ All users already have invoices!\n";
        exit;
    }

    echo "2. Preview of users to be processed:\n";
    foreach ($usersWithoutInvoices->take(5) as $user) {
        echo "   - ID: {$user->id} | {$user->name} | {$user->race_category} | Status: {$user->status}\n";
    }

    if ($usersWithoutInvoices->count() > 5) {
        $remaining = $usersWithoutInvoices->count() - 5;
        echo "   ... and {$remaining} more users\n";
    }

    echo "\n3. Do you want to proceed with invoice backfill? (y/N): ";
    $confirmation = trim(fgets(STDIN));

    if (strtolower($confirmation) !== 'y') {
        echo "❌ Invoice backfill cancelled by user\n";
        exit;
    }

    echo "\n4. Starting invoice backfill process...\n";

    $created = 0;
    $failed = 0;
    $skipped = 0;

    foreach ($usersWithoutInvoices as $user) {
        try {
            echo "   Processing User {$user->id} ({$user->name})...\n";
            
            // Skip users without required data
            if (!$user->race_category || !$user->email) {
                echo "     ⚠️  Skipped - missing required data (category: {$user->race_category}, email: {$user->email})\n";
                $skipped++;
                continue;
            }
            
            $invoice = $xenditService->createInvoice($user);
            
            if ($invoice && isset($invoice['success']) && $invoice['success'] && isset($invoice['data'])) {
                $invoiceData = $invoice['data'];
                
                $user->update([
                    'xendit_invoice_id' => $invoiceData['id'],
                    'xendit_invoice_url' => $invoiceData['invoice_url'],
                    'status' => 'registered'
                ]);
                
                $created++;
                echo "     ✅ Invoice created: {$invoiceData['id']} | Amount: {$invoiceData['amount']}\n";
                
            } else {
                $failed++;
                $errorMsg = isset($invoice['message']) ? $invoice['message'] : 'Unknown error';
                echo "     ❌ Failed to create invoice: {$errorMsg}\n";
            }
            
            // Small delay to avoid rate limiting
            usleep(100000); // 0.1 second
            
        } catch (\Exception $e) {
            $failed++;
            echo "     ❌ Exception: " . $e->getMessage() . "\n";
        }
    }

    echo "\n5. Invoice backfill complete!\n";
    echo "   ✅ Successfully created: {$created} invoices\n";
    echo "   ❌ Failed to create: {$failed} invoices\n";
    echo "   ⚠️  Skipped: {$skipped} users\n";

    // Final verification
    echo "\n6. Final verification:\n";
    $totalUsers = User::count();
    $usersWithExternalId = User::whereNotNull('xendit_external_id')->count();
    $usersWithInvoiceId = User::whereNotNull('xendit_invoice_id')->count();
    $usersWithInvoiceUrl = User::whereNotNull('xendit_invoice_url')->count();
    
    echo "   Total users: {$totalUsers}\n";
    echo "   Users with external_id: {$usersWithExternalId} (" . round(($usersWithExternalId/$totalUsers)*100, 1) . "%)\n";
    echo "   Users with invoice_id: {$usersWithInvoiceId} (" . round(($usersWithInvoiceId/$totalUsers)*100, 1) . "%)\n";
    echo "   Users with invoice_url: {$usersWithInvoiceUrl} (" . round(($usersWithInvoiceUrl/$totalUsers)*100, 1) . "%)\n";

    if ($usersWithInvoiceId >= $usersWithExternalId) {
        echo "\n🎉 All users with external_id now have invoices!\n";
    } else {
        $remaining = $usersWithExternalId - $usersWithInvoiceId;
        echo "\n⚠️  {$remaining} users still missing invoices\n";
    }

    echo "\n=== Backfill Process Complete ===\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
