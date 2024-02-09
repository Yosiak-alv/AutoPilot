<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::where('name','!=','Super-Admin')->pluck('name');

        $user = User::create([
            'name' => 'Administrator',
            'email' => 'Administrator@feyalegria.org.sv',
            'password' => bcrypt('password'),
            'branch_id' => 1,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('Super-Admin');
/* 
        User::factory(9)->create()->each(function ($user) use ($roles){
            $user->assignRole($roles->random(rand(1,$roles->count())));
        }); */

       
    }
}
