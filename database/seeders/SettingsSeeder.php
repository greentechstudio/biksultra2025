<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jersey Sizes
        $jerseySizes = [
            ['name' => 'XS', 'code' => 'XS', 'active' => true],
            ['name' => 'S', 'code' => 'S', 'active' => true],
            ['name' => 'M', 'code' => 'M', 'active' => true],
            ['name' => 'L', 'code' => 'L', 'active' => true],
            ['name' => 'XL', 'code' => 'XL', 'active' => true],
            ['name' => 'XXL', 'code' => 'XXL', 'active' => true],
            ['name' => 'XXXL', 'code' => 'XXXL', 'active' => true],
        ];

        foreach ($jerseySizes as $size) {
            JerseySize::create($size);
        }

        // Race Categories
        $raceCategories = [
            ['name' => '5K Fun Run', 'description' => '5K', 'price' => 75000, 'active' => true],
            ['name' => '10K Challenge', 'description' => '10K', 'price' => 125000, 'active' => true],
            ['name' => '21K Half Marathon', 'description' => '21K', 'price' => 175000, 'active' => true],
            ['name' => '42K Marathon', 'description' => '42K', 'price' => 250000, 'active' => true],
        ];

        foreach ($raceCategories as $category) {
            RaceCategory::create($category);
        }

        // Blood Types
        $bloodTypes = [
            ['name' => 'A+', 'active' => true],
            ['name' => 'A-', 'active' => true],
            ['name' => 'B+', 'active' => true],
            ['name' => 'B-', 'active' => true],
            ['name' => 'AB+', 'active' => true],
            ['name' => 'AB-', 'active' => true],
            ['name' => 'O+', 'active' => true],
            ['name' => 'O-', 'active' => true],
        ];

        foreach ($bloodTypes as $type) {
            BloodType::create($type);
        }

        // Event Sources
        $eventSources = [
            ['name' => 'Instagram', 'description' => 'Social Media Instagram', 'active' => true],
            ['name' => 'Facebook', 'description' => 'Social Media Facebook', 'active' => true],
            ['name' => 'WhatsApp Group', 'description' => 'WhatsApp Group', 'active' => true],
            ['name' => 'Teman/Keluarga', 'description' => 'Referensi dari teman atau keluarga', 'active' => true],
            ['name' => 'Website Resmi', 'description' => 'Website resmi event', 'active' => true],
            ['name' => 'Running Community', 'description' => 'Komunitas lari', 'active' => true],
            ['name' => 'Media Online', 'description' => 'Media online/portal berita', 'active' => true],
            ['name' => 'Lainnya', 'description' => 'Sumber informasi lainnya', 'active' => true],
        ];

        foreach ($eventSources as $source) {
            EventSource::create($source);
        }
    }
}
