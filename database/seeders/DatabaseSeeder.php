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
            /* StateTownDistrictSeeder::class,
            RepairStatusSeeder::class,
            BrandSeeder::class,
            BranchSeeder::class,
            WorkshopSeeder::class,
            CarSeeder::class,
            RepairSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class, */
           /*  RepairDetailsSeeder::class, */

           
            //Database seeder for production
            StateTownDistrictSeeder::class,
            RepairStatusSeeder::class,
            BrandSeeder::class,
            BranchSeeder::class,
            RolePermissionSeeder::class,
            WorkshopSeeder::class,
            UserSeeder::class,// => with only super admin user
          
       ]);
    }
}
