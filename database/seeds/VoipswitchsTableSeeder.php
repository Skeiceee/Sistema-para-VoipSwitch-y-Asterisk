<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoipswitchsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $voipswitchs = [
            1 => [
                'name' => 'Argentina',
                'conn_name' => 'argentina',
                'version' => '2.986.0.137'
            ],
            2 => [
                'name' => 'Wholesale',
                'conn_name' => 'wholesale',
                'version' => '2.986.0.137'
            ],
            3 => [
                'name' => 'Condell - Heavyuser',
                'conn_name' => 'condell.heavyuser',
                'version' => '2.0.0.954'
            ],
            4 => [
                'name' => 'Condell - Synergo',
                'conn_name' => 'condell.synergo',
                'version' => '2.0.0.954'
            ],
            5 => [
                'name' => 'Condell - Retail',
                'conn_name' => 'condell.retail',
                'version' => '2.0.0.954'
            ]
        ];

        foreach ($voipswitchs as $voipswitch) {
            DB::table('voipswitchs')->insert(
                [
                    'name' => $voipswitch['name'],
                    'conn_name' => $voipswitch['conn_name'],
                    'version' => $voipswitch['version'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
