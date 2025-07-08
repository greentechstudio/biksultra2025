<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateExistingUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with registration numbers and default values
        $users = User::whereNull('registration_number')->get();
        
        $counter = 1;
        $prefix = 'ASR' . date('Y'); // ASR2025
        
        foreach ($users as $user) {
            $user->update([
                'registration_number' => $prefix . sprintf('%04d', $counter),
                'is_active' => true,
                'payment_status' => $user->payment_confirmed ? 'paid' : 'pending'
            ]);
            
            $counter++;
        }
        
        $this->command->info("Updated {$users->count()} existing users with registration numbers");
    }
}
