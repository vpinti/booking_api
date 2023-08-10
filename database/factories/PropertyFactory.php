<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'address_street' => fake()->streetAddress(),
            'address_postcode' => fake()->postcode(),
            'lat' => fake()->latitude(),
            'long' => fake()->longitude(),
        ];
    }

    public function withImages($count = 2)
    {
        return $this->afterCreating(function ($property) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $property->addMedia(fake()->image())->toMediaCollection('images');
            }
        });
    }
}
