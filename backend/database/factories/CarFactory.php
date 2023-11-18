<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makes = ['Audi', 'Seat', 'Volkswagen', 'Volvo', 'Ford'];
        $models = ['A4', 'Leon', 'Golf', 'XC90', 'Focus'];
        $owner = User::inRandomOrder()->first();

        return [
            'owner_id' => $owner->id,
            'make' => $this->faker->randomElement($makes),
            'model' => $this->faker->randomElement($models),
            'vin' => $this->faker->unique()->bothify('??????????????????'),
            'year_of_manufacture' => $this->faker->numberBetween(1990, 2023),
        ];
    }
}
