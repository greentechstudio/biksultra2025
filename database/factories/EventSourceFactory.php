<?php

namespace Database\Factories;

use App\Models\EventSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventSourceFactory extends Factory
{
    protected $model = EventSource::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Instagram', 'Facebook', 'WhatsApp', 'Friend', 'Website', 'API']),
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