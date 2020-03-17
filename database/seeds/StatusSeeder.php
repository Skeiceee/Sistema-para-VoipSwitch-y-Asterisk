<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            1 => [
                'name' => 'Libre',
                'description' => 'Indica que algo esta libre.'
            ],
            2 => [
                'name' => 'Ocupado',
                'description' => 'Indica que algo esta ocupado.'
            ]
        ];

        foreach ($types as $type) {
            $now = Carbon::now();
            DB::table('status')->insert(
                [
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }
    }
}
