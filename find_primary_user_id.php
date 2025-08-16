<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FIND PRIMARY USER ID & EXTERNAL ID ===\n\n";

echo "Pilihan pencarian:\n";
echo "1. Cari berdasarkan nama peserta\n";
echo "2. Cari berdasarkan email\n";
echo "3. Cari berdasarkan WhatsApp\n";
echo "4. Lihat semua grup kolektif\n";
echo "5. Lihat data dengan ticket_type_id tertentu\n\n";

echo "Pilih opsi (1-5): ";
$option = trim(fgets(STDIN));

switch ($option) {
    case '1':
        searchByName();
        break;
    case '2':
        searchByEmail();
        break;
    case '3':
        searchByWhatsApp();
        break;
    case '4':
        showAllCollectiveGroups();
        break;
    case '5':
        searchByTicketType();
        break;
    default:
        echo "❌ Pilihan tidak valid\n";
        exit;
}

function searchByName() {
    echo "\nMasukkan nama peserta (minimal 3 karakter): ";
    $name = trim(fgets(STDIN));
    
    if (strlen($name) < 3) {
        echo "❌ Nama minimal 3 karakter\n";
        return;
    }
    
    $users = User::where('name', 'LIKE', "%{$name}%")
                 ->orderBy('created_at', 'desc')
                 ->get();
    
    displaySearchResults($users, "nama '{$name}'");
}

function searchByEmail() {
    echo "\nMasukkan email: ";
    $email = trim(fgets(STDIN));
    
    $users = User::where('email', 'LIKE', "%{$email}%")
                 ->orderBy('created_at', 'desc')
                 ->get();
    
    displaySearchResults($users, "email '{$email}'");
}

function searchByWhatsApp() {
    echo "\nMasukkan nomor WhatsApp (tanpa +62): ";
    $whatsapp = trim(fgets(STDIN));
    
    $users = User::where('whatsapp_number', 'LIKE', "%{$whatsapp}%")
                 ->orderBy('created_at', 'desc')
                 ->get();
    
    displaySearchResults($users, "WhatsApp '{$whatsapp}'");
}

function searchByTicketType() {
    echo "\nMasukkan Ticket Type ID (contoh: 25,24,23): ";
    $input = trim(fgets(STDIN));
    
    $ticketTypes = array_map('trim', explode(',', $input));
    $users = User::whereIn('ticket_type_id', $ticketTypes)
                 ->orderBy('created_at', 'desc')
                 ->get();
    
    displaySearchResults($users, "ticket type ID " . implode(', ', $ticketTypes));
}

function displaySearchResults($users, $searchTerm) {
    if ($users->isEmpty()) {
        echo "❌ Tidak ditemukan data untuk {$searchTerm}\n";
        return;
    }
    
    echo "\n✅ Ditemukan {$users->count()} hasil untuk {$searchTerm}:\n\n";
    
    // Group by collective vs individual
    $collectiveUsers = $users->filter(function($user) {
        return str_contains($user->xendit_external_id, 'AMAZING-COLLECTIVE-');
    });
    
    $individualUsers = $users->filter(function($user) {
        return str_contains($user->xendit_external_id, 'AMAZING-REG-');
    });
    
    // Show collective groups
    if (!$collectiveUsers->isEmpty()) {
        $groups = $collectiveUsers->groupBy('xendit_external_id');
        echo "🏃 REGISTRASI KOLEKTIF ({$groups->count()} grup):\n\n";
        
        foreach ($groups as $externalId => $groupUsers) {
            // Extract primary user ID from external ID
            preg_match('/AMAZING-COLLECTIVE-(\d+)-/', $externalId, $matches);
            $primaryUserId = $matches[1] ?? 'Unknown';
            
            echo "📋 GRUP: {$externalId}\n";
            echo "   Primary User ID: {$primaryUserId}\n";
            echo "   Jumlah Peserta: " . $groupUsers->count() . "\n";
            echo "   Invoice ID: " . ($groupUsers->first()->xendit_invoice_id ?: 'NONE') . "\n";
            echo "   Payment Status: " . $groupUsers->first()->payment_status . "\n";
            echo "   Total Fee: Rp " . number_format($groupUsers->sum('registration_fee'), 0, ',', '.') . "\n\n";
            
            echo "   📝 Daftar Peserta:\n";
            foreach ($groupUsers as $index => $user) {
                $isPrimary = ($user->id == $primaryUserId) ? " 👑 (PRIMARY/LEADER)" : "";
                echo "      " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->race_category}{$isPrimary}\n";
            }
            echo "\n" . str_repeat("-", 80) . "\n\n";
            
            echo "   💡 CARA REGENERATE:\n";
            echo "   php regenerate_collective_invoice_v2.php\n";
            echo "   Pilih opsi 1, lalu masukkan: {$primaryUserId} ATAU {$externalId}\n\n";
        }
    }
    
    // Show individual users
    if (!$individualUsers->isEmpty()) {
        echo "👤 REGISTRASI INDIVIDUAL ({$individualUsers->count()} peserta):\n\n";
        
        foreach ($individualUsers as $index => $user) {
            echo "   " . ($index + 1) . ". ID: {$user->id} | {$user->name} | {$user->race_category}\n";
            echo "      Email: {$user->email}\n";
            echo "      External ID: {$user->xendit_external_id}\n";
            echo "      Invoice: " . ($user->xendit_invoice_id ?: 'NONE') . "\n";
            echo "      Payment: {$user->payment_status}\n";
            echo "      Fee: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n\n";
            
            echo "      💡 CARA REGENERATE:\n";
            echo "      php regenerate_collective_invoice_v2.php\n";
            echo "      Pilih opsi 2, lalu masukkan: {$user->id}\n\n";
        }
    }
}

function showAllCollectiveGroups() {
    echo "\n=== SEMUA GRUP KOLEKTIF ===\n\n";
    
    $collectiveUsers = User::where('xendit_external_id', 'LIKE', 'AMAZING-COLLECTIVE-%')
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->groupBy('xendit_external_id');
    
    if ($collectiveUsers->isEmpty()) {
        echo "❌ Tidak ditemukan grup kolektif\n";
        return;
    }
    
    echo "📊 Total: " . $collectiveUsers->count() . " grup kolektif\n\n";
    
    $groupNumber = 1;
    foreach ($collectiveUsers as $externalId => $users) {
        // Extract primary user ID
        preg_match('/AMAZING-COLLECTIVE-(\d+)-/', $externalId, $matches);
        $primaryUserId = $matches[1] ?? 'Unknown';
        
        echo "🏃 GRUP {$groupNumber}: {$externalId}\n";
        echo "   Primary User ID: {$primaryUserId}\n";
        echo "   Peserta: " . $users->count() . " orang\n";
        echo "   Leader: " . $users->where('id', $primaryUserId)->first()->name ?? 'Unknown' . "\n";
        echo "   Status: " . $users->first()->payment_status . "\n";
        echo "   Total: Rp " . number_format($users->sum('registration_fee'), 0, ',', '.') . "\n";
        echo "   Tanggal: " . $users->first()->created_at->format('d/m/Y H:i') . "\n\n";
        
        $groupNumber++;
    }
    
    echo "💡 UNTUK REGENERATE GRUP TERTENTU:\n";
    echo "php regenerate_collective_invoice_v2.php\n";
    echo "Pilih opsi 1, lalu masukkan Primary User ID atau External ID\n\n";
}
