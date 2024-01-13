<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Repair;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RepairDetails>
 */
class RepairDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $repair_ids = Repair::pluck('id')->toArray();
        return [
            'repair_id' => fake()->randomElement($repair_ids),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 1000),
        ];
    }
}
