<?php

namespace Database\Seeders;

use App\Models\WorkShop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkShop::factory(10)->create();
    }
}
