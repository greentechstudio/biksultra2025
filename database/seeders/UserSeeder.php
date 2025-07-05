<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'whatsapp_verified' => true,
            'whatsapp_verified_at' => now(),
            'payment_confirmed' => true,
            'payment_confirmed_at' => now(),
            'payment_amount' => 100000,
            'payment_method' => 'admin',
            'payment_notes' => 'Admin account',
            'status' => 'active',
        ]);

        // Create sample users with different status
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'whatsapp_verified' => true,
            'whatsapp_verified_at' => now()->subDays(2),
            'payment_confirmed' => true,
            'payment_confirmed_at' => now()->subDays(1),
            'payment_amount' => 100000,
            'payment_method' => 'whatsapp',
            'payment_notes' => 'Transfer BCA',
            'status' => 'paid',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'whatsapp_verified' => true,
            'whatsapp_verified_at' => now()->subDays(1),
            'payment_confirmed' => false,
            'status' => 'verified',
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'whatsapp_verified' => false,
            'payment_confirmed' => false,
            'status' => 'pending',
        ]);

        User::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'phone' => '081234567894',
            'password' => Hash::make('password'),
            'whatsapp_verified' => false,
            'payment_confirmed' => false,
            'status' => 'pending',
        ]);
    }
}
