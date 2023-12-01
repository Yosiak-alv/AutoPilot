<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $district_ids = District::pluck('id')->toArray();
        return [
            'name' => fake()->name(),
            'address' => fake()->address(),
            'email' => fake()->unique()->email(),
            'telephone' => fake()->numerify('########'),
            'main' => fake()->boolean(),
            'district_id' => fake()->randomElement($district_ids),
        ];
    }
}
