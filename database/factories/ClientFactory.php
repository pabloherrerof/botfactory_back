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
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'population' => $this->faker->city(),
            'active' => $this->faker->boolean(),
            'birthday' => $this->faker->date(),
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'photo' => null,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
