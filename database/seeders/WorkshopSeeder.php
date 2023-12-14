<?php

namespace Database\Seeders;

use App\Models\WorkShop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;
class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* WorkShop::factory(10)->create(); */
        $workShopNames = [
            "Taller MotorPro",
            "Garaje Rápido y Fiable",
            "Mecánica Total",
            "AutoMantenimiento",
            "GaragePro",
            "Mecánicos Expertos",
            "Reparaciones de Confianza",
            "Garaje Master",
            "Taller de Autos Profesional",
            "AutoCuidado",
            "Mecánica en Acción",
            "Taller de Emergencia",
            "Garaje Veloz",
            "Taller de Automóviles Premium",
        ];
        $district_ids = District::pluck('id')->toArray();
        
        WorkShop::factory(count($workShopNames ))->sequence(fn($sequence) => [
            'name' => $workShopNames [$sequence->index],
            'address' => fake()->address(),
            'email' => fake()->unique()->email(),
            'telephone' => fake()->numerify('########'),
            'district_id' => fake()->randomElement($district_ids),
        ])->create();
    }
}
