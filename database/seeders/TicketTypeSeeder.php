<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;
use App\Models\RaceCategory;
use Carbon\Carbon;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing ticket types without truncating (to avoid foreign key issues)
        TicketType::query()->delete();

        // Get race categories
        $raceCategories = RaceCategory::active()->get();
        
        if ($raceCategories->isEmpty()) {
            $this->command->info('No race categories found. Please run RaceCategorySeeder first.');
            return;
        }
        
        foreach ($raceCategories as $raceCategory) {
            // Early Bird Tickets (1 Juli - 15 Juli 2025)
            TicketType::create([
                'name' => 'Early Bird',
                'race_category_id' => $raceCategory->id,
                'price' => $raceCategory->price * 0.8, // 20% discount for early bird
                'start_date' => Carbon::create(2025, 7, 1, 0, 0, 0),
                'end_date' => Carbon::create(2025, 7, 15, 23, 59, 59),
                'quota' => $raceCategory->name === '21K' ? 200 : 300, // 21K limited to 200, others 300
                'registered_count' => 0,
                'is_active' => true
            ]);

            // Regular Tickets (16 Juli - 31 Agustus 2025)
            TicketType::create([
                'name' => 'Regular',
                'race_category_id' => $raceCategory->id,
                'price' => $raceCategory->price, // Use original price from race category
                'start_date' => Carbon::create(2025, 7, 16, 0, 0, 0),
                'end_date' => Carbon::create(2025, 8, 31, 23, 59, 59),
                'quota' => $raceCategory->name === '21K' ? 300 : 500, // 21K limited to 300, others 500
                'registered_count' => 0,
                'is_active' => true
            ]);
        }
    }
}
