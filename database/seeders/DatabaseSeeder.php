<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            StateTownDistrictSeeder::class,
            RepairStatusSeeder::class,
            BrandSeeder::class,
            BranchSeeder::class,
            WorkshopSeeder::class,
            CarSeeder::class,
            RepairSeeder::class,
            UserSeeder::class,
           /*  RepairDetailsSeeder::class, */
       ]);
    }
}
