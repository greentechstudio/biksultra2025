<?php

namespace Database\Factories;

use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Early Bird', 'Regular', 'Late Registration', 'Wakaf']),
            'price' => $this->faker->randomElement([50000, 75000, 100000, 125000]),
            'quota' => $this->faker->numberBetween(50, 500),
            'registered_count' => 0,
            'active' => 1,
            'description' => $this->faker->sentence(),
            'available_from' => now()->subDays(30),
            'available_until' => now()->addDays(30),
        ];
    }

    public function wakaf(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Wakaf Participant',
                'price' => 100000,
                'quota' => 100,
                'description' => 'Special ticket for wakaf participants',
            ];
        });
    }

    public function earlyBird(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Early Bird',
                'price' => 50000,
                'quota' => 200,
                'description' => 'Early bird pricing for early registration',
            ];
        });
    }

    public function regular(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Regular',
                'price' => 75000,
                'quota' => 300,
                'description' => 'Regular pricing',
            ];
        });
    }

    public function full(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'quota' => 10,
                'registered_count' => 10,
            ];
        });
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