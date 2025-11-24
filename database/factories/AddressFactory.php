<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'city_id'   => City::inRandomOrder()->first()?->id ?? City::factory(),
            'street'    => $this->faker->streetAddress(),
            'latitude'  => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'details'   => $this->faker->optional()->sentence(8),
        ];
    }
}
