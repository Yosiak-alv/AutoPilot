<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand_ids = Brand::pluck('id')->toArray();
        return [
            'name' => fake()->name(),
            'brand_id' => fake()->randomElement($brand_ids),
        ];
    }
}
