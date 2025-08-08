<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\XenditService;
use App\Models\User;
use App\Models\TicketType;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== INVOICE MANAGEMENT TOOL ===\n\n";
echo "Pilihan:\n";
echo "1. Regenerate invoice kolektif (AMAZING-COLLECTIVE- format)\n";
echo "2. Regenerate invoice individual (AMAZING-REG- tunggal)\n";
echo "3. Update kategori peserta tertentu + regenerate invoice\n";
echo "4. Cek data berdasarkan User ID\n";
echo "5. Cari grup berdasarkan Invoice ID (1 Invoice ID = 1 Kelompok)\n";
echo "6. Cari grup berdasarkan Primary User ID (External ID sama)\n\n";

echo "Pilih opsi (1-6): ";
$option = trim(fgets(STDIN));

switch ($option) {
    case '1':
        regenerateCollectiveInvoice();
        break;
    case '2': 
        regenerateIndividualInvoice();
        break;
    case '3':
        updateParticipantCategory();
        break;
    case '4':
        checkUserData();
        break;
    case '5':
        findGroupByInvoiceId();
        break;
    case '6':
        findGroupByPrimaryUserId();
        break;
    default:
        echo "❌ Pilihan tidak valid\n";
        exit;
}

function regenerateCollectiveInvoice() {
    echo "\n=== REGENERATE COLLECTIVE INVOICE ===\n";
    echo "Masukkan Primary User ID atau External ID: ";
    $input = trim(fgets(STDIN));
    
    if (empty($input)) {
        echo "❌ Input tidak boleh kosong\n";
        return;
    }

    try {
        $xenditService = new XenditService();
        
        // Find users in the collective group
        if (is_numeric($input)) {
            $primaryUserId = $input;
            $users = User::where('xendit_external_id', 'LIKE', "AMAZING-COLLECTIVE-{$primaryUserId}-%")
                         ->orderBy('created_at', 'asc')
                         ->get();
        } else {
            $users = User::where('xendit_external_id', $input)
                         ->orderBy('created_at', 'asc')
                         ->get();
            
            if ($users->isEmpty()) {
                $users = User::where('xendit_external_id', 'LIKE', "{$input}%")
                             ->orderBy('created_at', 'asc')
                             ->get();
            }
        }

        if ($users->isEmpty()) {
            echo "❌ Tidak ditemukan user dengan ID/External ID: {$input}\n";
            return;
        }

        // Check if any user already paid
        $paidUsers = $users->where('payment_status', 'paid');
        if (!$paidUsers->isEmpty()) {
            echo "⚠️  PERINGATAN: Ada {$paidUsers->count()} peserta yang sudah PAID:\n";
            foreach ($paidUsers as $user) {
                echo "   - {$user->name} (ID: {$user->id})\n";
            }
            echo "\nLanjutkan regenerasi? Ini akan reset status pembayaran mereka! (y/N): ";
            $confirm = trim(fgets(STDIN));
            if (strtolower($confirm) !== 'y') {
                echo "❌ Regenerasi dibatalkan\n";
                return;
            }
        }

        processCollectiveRegeneration($users, $xenditService);
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n";
    }
}

function processCollectiveRegeneration($users, $xenditService) {
    echo "✅ Ditemukan {$users->count()} peserta dalam grup kolektif:\n\n";
    
    $totalAmount = 0;
    $participantDetails = [];
    
    foreach ($users as $index => $user) {
        echo "   " . ($index + 1) . ". {$user->name} - {$user->race_category} - Status: {$user->status}\n";
        echo "      Email: {$user->email}\n";
        echo "      Current Invoice: " . ($user->xendit_invoice_id ?: 'NONE') . "\n";
        echo "      Payment Status: {$user->payment_status}\n\n";
        
        // Validate price for regeneration
        $validatedPrice = $xenditService->getCollectivePrice($user->race_category);
        if ($validatedPrice !== false) {
            $totalAmount += $validatedPrice;
            
            // Update user with correct price if needed
            if ($user->registration_fee != $validatedPrice) {
                echo "      ⚠️  Updating price: Rp " . number_format($user->registration_fee, 0, ',', '.') . 
                     " → Rp " . number_format($validatedPrice, 0, ',', '.') . "\n";
                $user->update(['registration_fee' => $validatedPrice]);
            }
            
            $participantDetails[] = [
                'name' => $user->name,
                'category' => $user->race_category,
                'fee' => $validatedPrice
            ];
        } else {
            echo "      ❌ Cannot validate price for category: {$user->race_category}\n";
            return;
        }
    }
    
    echo "💰 Total Amount: Rp " . number_format($totalAmount, 0, ',', '.') . "\n\n";
    
    echo "Lanjutkan regenerasi invoice? (y/N): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) !== 'y') {
        echo "❌ Regenerasi dibatalkan\n";
        return;
    }
    
    // Clear existing invoice data
    foreach ($users as $user) {
        $user->update([
            'xendit_invoice_id' => null,
            'xendit_invoice_url' => null,
            'payment_status' => 'pending'
        ]);
    }
    
    echo "🔄 Membersihkan data invoice lama...\n";
    
    // Create new collective invoice
    $primaryUser = $users->first();
    $description = "Amazing Sultra Run - Registrasi Kolektif Regenerated (" . $users->count() . " peserta)";
    
    echo "🆕 Membuat invoice baru...\n";
    
    $paymentResult = $xenditService->createCollectiveInvoice(
        $primaryUser,
        $participantDetails,
        $totalAmount,
        $description
    );
    
    if ($paymentResult['success']) {
        // Update all users with new invoice
        foreach ($users as $user) {
            $user->update([
                'xendit_invoice_id' => $paymentResult['invoice_id'],
                'xendit_invoice_url' => $paymentResult['invoice_url'],
                'payment_description' => $description,
                'status' => 'registered'
            ]);
        }
        
        echo "✅ Invoice berhasil di-regenerate!\n\n";
        echo "📋 Detail Invoice Baru:\n";
        echo "   Invoice ID: {$paymentResult['invoice_id']}\n";
        echo "   External ID: {$paymentResult['external_id']}\n";
        echo "   Amount: Rp " . number_format($paymentResult['amount'], 0, ',', '.') . "\n";
        echo "   URL: {$paymentResult['invoice_url']}\n\n";
        
        echo "📱 Link pembayaran baru telah di-update untuk semua peserta.\n";
        echo "🔗 Group Leader dapat menggunakan link: {$paymentResult['invoice_url']}\n";
        
    } else {
        echo "❌ Gagal membuat invoice baru: " . $paymentResult['message'] . "\n";
    }
}

function regenerateIndividualInvoice() {
    echo "\n=== REGENERATE INDIVIDUAL INVOICE ===\n";
    echo "Masukkan User ID: ";
    $userId = trim(fgets(STDIN));
    
    if (!is_numeric($userId)) {
        echo "❌ User ID harus berupa angka\n";
        return;
    }

    try {
        $user = User::find($userId);
        if (!$user) {
            echo "❌ User dengan ID {$userId} tidak ditemukan\n";
            return;
        }

        echo "📋 Data User:\n";
        echo "   Nama: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Kategori: {$user->race_category}\n";
        echo "   Current Invoice: " . ($user->xendit_invoice_id ?: 'NONE') . "\n";
        echo "   Payment Status: {$user->payment_status}\n";
        echo "   Registration Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n\n";

        if ($user->payment_status === 'paid') {
            echo "⚠️  PERINGATAN: User ini sudah PAID!\n";
            echo "Lanjutkan regenerasi? (y/N): ";
            $confirm = trim(fgets(STDIN));
            if (strtolower($confirm) !== 'y') {
                echo "❌ Regenerasi dibatalkan\n";
                return;
            }
        }

        $xenditService = new XenditService();
        
        // Clear existing invoice
        $user->update([
            'xendit_invoice_id' => null,
            'xendit_invoice_url' => null,
            'payment_status' => 'pending'
        ]);

        echo "🔄 Membersihkan invoice lama...\n";
        echo "🆕 Membuat invoice baru...\n";

        $paymentResult = $xenditService->createInvoice($user);

        if ($paymentResult['success']) {
            echo "✅ Invoice berhasil di-regenerate!\n\n";
            echo "📋 Detail Invoice Baru:\n";
            echo "   Invoice ID: " . $paymentResult['data']['id'] . "\n";
            echo "   Amount: Rp " . number_format($paymentResult['data']['amount'], 0, ',', '.') . "\n";
            echo "   URL: " . $paymentResult['invoice_url'] . "\n\n";
        } else {
            echo "❌ Gagal membuat invoice: " . $paymentResult['error'] . "\n";
        }

    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

function updateParticipantCategory() {
    echo "\n=== UPDATE KATEGORI PESERTA ===\n";
    echo "Masukkan User ID (pisahkan dengan koma untuk multiple): ";
    $input = trim(fgets(STDIN));
    
    $userIds = array_map('trim', explode(',', $input));
    $users = User::whereIn('id', $userIds)->get();
    
    if ($users->isEmpty()) {
        echo "❌ Tidak ditemukan user dengan ID tersebut\n";
        return;
    }

    echo "\n📋 Data User yang akan diubah:\n";
    foreach ($users as $index => $user) {
        echo "   " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->race_category} | {$user->payment_status}\n";
    }

    // Get available categories
    $ticketTypes = TicketType::where('is_active', true)->with('raceCategory')->get();
    echo "\n🎫 Kategori yang tersedia:\n";
    foreach ($ticketTypes as $ticket) {
        echo "   {$ticket->id}. {$ticket->raceCategory->name} - Rp " . number_format($ticket->price, 0, ',', '.') . "\n";
    }

    echo "\nMasukkan Ticket Type ID baru: ";
    $newTicketTypeId = trim(fgets(STDIN));
    
    $newTicketType = TicketType::find($newTicketTypeId);
    if (!$newTicketType) {
        echo "❌ Ticket Type tidak valid\n";
        return;
    }

    echo "\n✅ Akan mengubah ke: {$newTicketType->raceCategory->name} - Rp " . number_format($newTicketType->price, 0, ',', '.') . "\n";
    echo "Lanjutkan? (y/N): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) !== 'y') {
        echo "❌ Update dibatalkan\n";
        return;
    }

    try {
        foreach ($users as $user) {
            $user->update([
                'ticket_type_id' => $newTicketType->id,
                'race_category' => $newTicketType->raceCategory->name,
                'registration_fee' => $newTicketType->price,
                'xendit_invoice_id' => null,
                'xendit_invoice_url' => null,
                'payment_status' => 'pending'
            ]);
            echo "✅ Updated: {$user->name}\n";
        }

        echo "\n🔄 Regenerating invoices...\n";
        
        // Regenerate invoices
        $xenditService = new XenditService();
        foreach ($users as $user) {
            $paymentResult = $xenditService->createInvoice($user);
            if ($paymentResult['success']) {
                echo "✅ Invoice created for: {$user->name}\n";
            } else {
                echo "❌ Failed to create invoice for: {$user->name}\n";
            }
        }

    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

function checkUserData() {
    echo "\n=== CEK DATA USER ===\n";
    echo "Masukkan User ID: ";
    $userId = trim(fgets(STDIN));
    
    if (!is_numeric($userId)) {
        echo "❌ User ID harus berupa angka\n";
        return;
    }

    try {
        $user = User::with('ticketType.raceCategory')->find($userId);
        if (!$user) {
            echo "❌ User dengan ID {$userId} tidak ditemukan\n";
            return;
        }

        echo "\n📋 DETAIL USER:\n";
        echo "   ID: {$user->id}\n";
        echo "   Nama: {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   WhatsApp: {$user->whatsapp_number}\n";
        echo "   Ticket Type ID: {$user->ticket_type_id}\n";
        echo "   Race Category: {$user->race_category}\n";
        echo "   Registration Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
        echo "   External ID: {$user->xendit_external_id}\n";
        echo "   Invoice ID: " . ($user->xendit_invoice_id ?: 'NONE') . "\n";
        echo "   Invoice URL: " . ($user->xendit_invoice_url ? 'YES' : 'NONE') . "\n";
        echo "   Payment Status: {$user->payment_status}\n";
        echo "   Status: {$user->status}\n";
        echo "   Created: {$user->created_at}\n";

        if ($user->ticketType) {
            echo "\n🎫 TICKET TYPE INFO:\n";
            echo "   Name: {$user->ticketType->name}\n";
            echo "   Price: Rp " . number_format($user->ticketType->price, 0, ',', '.') . "\n";
            echo "   Active: " . ($user->ticketType->is_active ? 'YES' : 'NO') . "\n";
        }

    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Fungsi untuk mencari grup berdasarkan Xendit Invoice ID
function findGroupByInvoiceId() {
    echo "\n=== 🔍 CARI GRUP BERDASARKAN XENDIT INVOICE ID ===\n";
    echo "💡 KONSEP: Same Invoice ID = Same Group = 1 Payment untuk semua peserta\n";
    echo "Masukkan Xendit Invoice ID (contoh: 670b2c43f37063005b7e92d8): ";
    $invoiceId = trim(fgets(STDIN));
    
    if (empty($invoiceId)) {
        echo "❌ Invoice ID tidak boleh kosong\n";
        return;
    }
    
    // Cari semua user dengan invoice ID yang sama
    $users = \App\Models\User::where('xendit_invoice_id', $invoiceId)
                            ->orderBy('id')
                            ->get();
    
    if ($users->isEmpty()) {
        echo "❌ Tidak ditemukan data dengan Invoice ID: $invoiceId\n";
        return;
    }
    
    echo "\n✅ Ditemukan " . $users->count() . " peserta dengan Invoice ID: $invoiceId\n";
    echo "=== DETAIL GRUP ===\n";
    
    foreach ($users as $index => $user) {
        echo ($index + 1) . ". ID: {$user->id} | Nama: {$user->name} | Email: {$user->email}\n";
        echo "   External ID: {$user->xendit_external_id}\n";
        echo "   Ticket Type ID: {$user->ticket_type_id}\n";
        echo "   Status: {$user->payment_status}\n";
        echo "   Dibuat: {$user->created_at}\n";
        echo "   ----------------------------------------\n";
    }
    
    // Analisis: Jika sama xendit_invoice_id = 1 kelompok collective
    echo "📊 ANALISIS GRUP:\n";
    echo "   ✅ Semua peserta memiliki xendit_invoice_id yang SAMA: $invoiceId\n";
    echo "   🎯 Ini menunjukkan mereka adalah 1 KELOMPOK COLLECTIVE\n";
    echo "   💡 PRINSIP: Same Invoice ID = Same Group = 1 Payment untuk semua\n";
    
    $externalIds = $users->pluck('xendit_external_id')->unique();
    if ($externalIds->count() == 1) {
        $externalId = $externalIds->first();
        if (strpos($externalId, 'AMAZING-COLLECTIVE-') === 0) {
            echo "   � Format External ID: COLLECTIVE (standar)\n";
        } elseif (strpos($externalId, 'AMAZING-REG-') === 0) {
            echo "   📝 Format External ID: AMAZING-REG- (collective non-standar)\n";
        } else {
            echo "   � Format External ID: Custom format\n";
        }
    } else {
        echo "   📝 External ID berbeda-beda, tapi tetap 1 grup karena invoice ID sama\n";
    }
    
    echo "\nApakah Anda ingin regenerate invoice untuk grup ini? (y/n): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) === 'y') {
        regenerateInvoiceForGroup($users);
    }
}

function regenerateInvoiceForGroup($users) {
    if ($users->isEmpty()) {
        echo "❌ Tidak ada data user untuk diproses\n";
        return;
    }
    
    $firstUser = $users->first();
    $oldInvoiceId = $firstUser->xendit_invoice_id;
    
    echo "\n=== 🔄 REGENERATING INVOICE UNTUK GRUP ===\n";
    echo "Old Invoice ID: $oldInvoiceId\n";
    echo "Jumlah peserta: " . $users->count() . "\n";
    
    try {
        $xenditService = new XenditService();
        
        // Bersihkan invoice lama untuk semua peserta
        foreach ($users as $user) {
            $user->update([
                'xendit_invoice_id' => null,
                'xendit_invoice_url' => null,
                'payment_status' => 'pending'
            ]);
        }
        echo "🧹 Membersihkan invoice lama untuk semua peserta...\n";
        
        // SELALU buat 1 collective invoice untuk grup yang sama xendit_invoice_id
        // Karena xendit_invoice_id yang sama = 1 kelompok = 1 invoice
        echo "🎯 Membuat 1 collective invoice untuk seluruh grup...\n";
        
        // Hitung total amount dan siapkan participant details
        $totalAmount = 0;
        $participantDetails = [];
        
        foreach ($users as $user) {
            $validatedPrice = $xenditService->getCollectivePrice($user->race_category);
            if ($validatedPrice !== false) {
                $totalAmount += $validatedPrice;
                
                // Update price jika diperlukan
                if ($user->registration_fee != $validatedPrice) {
                    echo "💰 Updating price untuk {$user->name}: Rp " . number_format($user->registration_fee, 0, ',', '.') . 
                         " → Rp " . number_format($validatedPrice, 0, ',', '.') . "\n";
                    $user->update(['registration_fee' => $validatedPrice]);
                }
                
                $participantDetails[] = [
                    'name' => $user->name,
                    'category' => $user->race_category,
                    'fee' => $validatedPrice
                ];
            } else {
                echo "❌ Cannot validate price for category: {$user->race_category}\n";
                return;
            }
        }
        
        // Buat collective invoice
        $primaryUser = $users->first();
        $description = "Amazing Sultra Run - Collective Registration Regenerated (" . $users->count() . " peserta)";
        
        $paymentResult = $xenditService->createCollectiveInvoice(
            $primaryUser,
            $participantDetails,
            $totalAmount,
            $description
        );
        
        if ($paymentResult['success']) {
            // Update semua peserta dengan invoice baru yang sama
            foreach ($users as $user) {
                $user->update([
                    'xendit_invoice_id' => $paymentResult['invoice_id'],
                    'xendit_invoice_url' => $paymentResult['invoice_url'],
                    'payment_description' => $description,
                    'status' => 'registered'
                ]);
            }
            
            echo "✅ Collective invoice berhasil dibuat!\n";
            echo "   📋 New Invoice ID: {$paymentResult['invoice_id']}\n";
            echo "   💰 Total Amount: Rp " . number_format($totalAmount, 0, ',', '.') . "\n";
            echo "   🔗 Invoice URL: {$paymentResult['invoice_url']}\n";
            echo "\n🎯 PENTING: Semua {$users->count()} peserta sekarang menggunakan invoice yang SAMA\n";
            echo "   Group leader dapat menggunakan link pembayaran untuk bayar semua peserta sekaligus.\n";
            
        } else {
            echo "❌ Gagal membuat collective invoice: {$paymentResult['message']}\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Fungsi untuk mencari grup berdasarkan Primary User ID  
function findGroupByPrimaryUserId() {
    echo "\n=== 🔍 CARI GRUP BERDASARKAN PRIMARY USER ID ===\n";
    echo "Masukkan Primary User ID: ";
    $primaryUserId = trim(fgets(STDIN));
    
    if (!is_numeric($primaryUserId)) {
        echo "❌ User ID harus berupa angka\n";
        return;
    }
    
    try {
        $primaryUser = User::find($primaryUserId);
        if (!$primaryUser) {
            echo "❌ User dengan ID {$primaryUserId} tidak ditemukan\n";
            return;
        }
        
        echo "\n📋 PRIMARY USER:\n";
        echo "   ID: {$primaryUser->id}\n";
        echo "   Nama: {$primaryUser->name}\n";
        echo "   External ID: {$primaryUser->xendit_external_id}\n";
        echo "   Invoice ID: {$primaryUser->xendit_invoice_id}\n";
        
        // Cari external ID pattern
        $externalId = $primaryUser->xendit_external_id;
        if (strpos($externalId, 'AMAZING-COLLECTIVE-') === 0) {
            // Format collective standar
            $pattern = $externalId;
            echo "   Format: COLLECTIVE (standar)\n\n";
        } elseif (strpos($externalId, 'AMAZING-REG-') === 0) {
            // Format collective dengan AMAZING-REG-
            $pattern = $externalId;
            echo "   Format: COLLECTIVE (AMAZING-REG-)\n\n";
        } else {
            echo "   Format: Individual atau tidak dikenal\n\n";
            return;
        }
        
        // Cari grup berdasarkan external ID yang sama
        $groupUsers = User::where('xendit_external_id', $pattern)
                         ->orderBy('id')
                         ->get();
        
        echo "✅ Ditemukan " . $groupUsers->count() . " peserta dalam grup:\n";
        foreach ($groupUsers as $index => $user) {
            echo "   " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->email}\n";
            echo "      Ticket Type: {$user->ticket_type_id} | Status: {$user->payment_status}\n";
        }
        
        echo "\nApakah Anda ingin regenerate invoice untuk grup ini? (y/n): ";
        $confirm = trim(fgets(STDIN));
        
        if (strtolower($confirm) === 'y') {
            processCollectiveRegeneration($groupUsers, new XenditService());
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}
