<?php

namespace Database\Factories;

use App\Models\BloodType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodTypeFactory extends Factory
{
    protected $model = BloodType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
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