<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::factory()->create([
            'name' => 'Fe y Alegria Antiguo Cuscatlan',
            'address' => 'Calle el Mediterráneo, No. 662 Colonia Jardines de Guadalupe.',
            'telephone' => '22431282',
            'email' => 'comunicacionesfya@feyalegria.org.sv',
            'main' => true,
            'district_id' => 81,
        ]);
        Branch::factory()->create([
            'name' => 'Fe y Alegria Zacamil',
            'address' => 'Calle Las Margaritas, Mejicanos, El Salvador',
            'telephone' => '22431282',
            'email' => 'feyalegria2@feyalegria.com',
            'main' => false,
            'district_id' => 191,
        ]);
        Branch::factory()->create([
            'name' => 'Fe y Alegria San Miguel',
            'address' => 'Avenida Los Cisneros, San Miguel, El Salvador',
            'telephone' => '25431282',
            'email' => 'feyalegria4@feyalegria.com',
            'main' => false,
            'district_id' => 167,
        ]);
        Branch::factory()->create([
            'name' => 'Fe y Alegria Santa Ana',
            'address' => 'Sobre 25 AV Sur, Santa Ana, El Salvador',
            'telephone' => '22131282',
            'email' => 'feyalegria5@feyalegria.com',
            'main' => false,
            'district_id' => 215,
        ]);
    }
}
