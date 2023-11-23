<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstname(),
            'surname' => $this->faker->lastname(),
            'phone' => $this->faker->e164PhoneNumber(),
            'avatar_url' => $this->faker->image(),
            'password' => bcrypt('password')
        ];
    }
}
