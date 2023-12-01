<?php

namespace Database\Seeders;

use App\Models\RepairStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepairStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = collect([
            'Aceptado',
            'Rechazado',
            'Completado',
            'Cancelado',
            'En Proceso',
        ]);
        RepairStatus::factory(count($status))->sequence(fn($sequence) => [
            'name' => $status[$sequence->index]
        ])->create();
    }
}
