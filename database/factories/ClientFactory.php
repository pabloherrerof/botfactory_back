<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'population' => $this->faker->city(),
            'active' => $this->faker->boolean(),
            'category' => $this->faker->randomElement(['A', 'B', 'C']),
            'photo' => $this->faker->imageUrl(640, 480, 'people', true),
        ];
    }
}
