<?php

namespace Database\Factories;

use App\Models\JerseySize;
use Illuminate\Database\Eloquent\Factories\Factory;

class JerseySizeFactory extends Factory
{
    protected $model = JerseySize::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
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