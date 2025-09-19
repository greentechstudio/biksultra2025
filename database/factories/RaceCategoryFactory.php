<?php

namespace Database\Factories;

use App\Models\RaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaceCategoryFactory extends Factory
{
    protected $model = RaceCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['5K', '10K', '21K', 'Fun Run']),
            'distance' => $this->faker->randomElement([5, 10, 21, 3]),
            'active' => 1,
            'description' => $this->faker->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => 0,
            ];
        });
    }
}