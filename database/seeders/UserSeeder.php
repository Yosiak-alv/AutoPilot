<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(9)->create();

        User::create([
            'name' => 'Josias Alvarenga',
            'email' => 'josias@example.com',
            'password' => bcrypt('password'),
            'branch_id' => 1,
        ]);
    }
}
