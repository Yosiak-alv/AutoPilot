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
            'name' => 'Fe y Alegria Central',
            'address' => 'No. 123, 1st Floor, Main Street, Yangon',
            'telephone' => '78085392',
            'email' => 'feyalegria@feyalegria.com',
            'main' => true,
            'district_id' => 1,
        ]);

        Branch::factory(2)->create();
    }
}
