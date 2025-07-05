<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@asr.com'],
            [
                'name' => 'Administrator ASR',
                'email' => 'admin@asr.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '081234567890',
                'whatsapp_number' => '6281234567890',
                'whatsapp_verified' => true,
                'whatsapp_verified_at' => now(),
                'payment_confirmed' => true,
                'payment_confirmed_at' => now(),
                'payment_amount' => 0,
                'status' => 'active',
                'gender' => 'Laki-laki',
                'birth_place' => 'Jakarta',
                'birth_date' => '1990-01-01',
                'address' => 'Jakarta, Indonesia',
                'jersey_size' => 'L',
                'race_category' => '10K',
                'emergency_contact_1' => 'Emergency Admin - 081234567891',
                'blood_type' => 'O',
                'occupation' => 'Administrator',
                'event_source' => 'Internal',
                'profile_edited' => false,
            ]
        );

        echo "Admin user created successfully!\n";
        echo "Email: admin@asr.com\n";
        echo "Password: admin123\n";
    }
}
