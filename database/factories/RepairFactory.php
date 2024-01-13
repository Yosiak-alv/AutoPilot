<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{WorkShop,RepairStatus, Car};
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repair>
 */
class RepairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $work_shop_ids = Workshop::pluck('id')->toArray();
        $repair_status_ids = RepairStatus::pluck('id')->toArray();
        $car_ids = Car::pluck('id')->toArray();
        
        return [
            'car_id' => fake()->randomElement($car_ids),
            'repair_status_id' => fake()->randomElement($repair_status_ids),
            'work_shop_id' => fake()->randomElement($work_shop_ids),
            'total' => fake()->randomFloat(2, 5, 500)
        ];
    }
}
