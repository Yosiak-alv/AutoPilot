<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Model;
use App\Models\Branch;
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
        $model_ids = Model::pluck('id')->toArray();
        $branch_ids = Branch::pluck('id')->toArray();
        return [
            'image' => '',
            'plates' => fake()->unique()->regexify('/^[A-Z0-9]{7}$/'),
            'VIN' => fake()->unique()->regexify('/\b[(A-H|J-N|P|R-Z|0-9)]{17}\b/'),
            'current_mileage' => fake()->numberBetween(1000, 100000),
            'year' => fake()->numberBetween(1990, 2024),
            
            'model_id' => fake()->randomElement($model_ids),
            'branch_id' => fake()->randomElement($branch_ids),
        ];
    }
}
