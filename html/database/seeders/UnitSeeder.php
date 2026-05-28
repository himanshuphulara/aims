<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'unit_name' => '1st Infantry Battalion',
                'description' => 'First Infantry Battalion of the Army',
                'is_active' => true,
            ],
            [
                'unit_name' => '2nd Artillery Regiment',
                'description' => 'Second Artillery Regiment specialized in heavy artillery',
                'is_active' => true,
            ],
            [
                'unit_name' => '3rd Armored Division',
                'description' => 'Third Armored Division with tank warfare capabilities',
                'is_active' => true,
            ],
            [
                'unit_name' => '4th Engineering Corps',
                'description' => 'Fourth Engineering Corps for construction and demolition',
                'is_active' => true,
            ],
            [
                'unit_name' => '5th Signal Battalion',
                'description' => 'Fifth Signal Battalion for communications',
                'is_active' => true,
            ],
            [
                'unit_name' => '6th Medical Corps',
                'description' => 'Sixth Medical Corps for field medical support',
                'is_active' => true,
            ],
            [
                'unit_name' => '7th Logistics Company',
                'description' => 'Seventh Logistics Company for supply chain management',
                'is_active' => false, // Example of inactive unit
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
