<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $measurementUnitData = [
            [
                'name' => 'Cups',
                'us_customary_unit' => true
            ],
            [
                'name' => 'Ounces',
                'us_customary_unit' => true
            ],
            [
                'name' => 'Tablespoons',
                'us_customary_unit' => true
            ],
            [
                'name' => 'Grams',
                'us_customary_unit' => false
            ],
            [
                'name' => 'Liters',
                'us_customary_unit' => false
            ],
        ];

        foreach($measurementUnitData as $data)
        {
            MeasurementUnit::updateOrCreate(
                [
                    'name' => $data['name']
                ],
                [
                    'us_customary_unit' => $data['us_customary_unit']
                ]
            );
        }
    }
}
