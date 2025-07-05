<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RaceCategory;

class RaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '5K',
                'description' => 'Kategori 5 Kilometer',
                'price' => 125000,
                'active' => true,
            ],
            [
                'name' => '10K',
                'description' => 'Kategori 10 Kilometer',
                'price' => 150000,
                'active' => true,
            ],
            [
                'name' => '21K',
                'description' => 'Kategori Half Marathon (21K)',
                'price' => 175000,
                'active' => true,
            ],
            [
                'name' => '42K',
                'description' => 'Kategori Full Marathon (42K)',
                'price' => 200000,
                'active' => true,
            ],
        ];

        foreach ($categories as $category) {
            RaceCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
