<?php

namespace Database\Seeders;

use App\Models\Repair;
use App\Models\RepairDetails;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $repairs = Repair::factory(25)->create()->each(function ($repair) {
            $sub_total = 0;
            $taxes = 0;

            $repair_details = RepairDetails::factory(rand(1, 10))->create();
            foreach ($repair_details as $item) {
                $item->repair_id = $repair->id;
                $sub_total += $item->price;
                $taxes += $item->taxes;
                $item->save();
            }

            $repair->sub_total = $sub_total;
            $repair->taxes = $taxes;
            $repair->total =  $taxes + $sub_total;
            $repair->save();
        });
    }
}
