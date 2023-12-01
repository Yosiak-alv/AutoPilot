<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\District;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
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
            'district_id' => fake()->randomElement($district_ids),
        ];
    }
}
