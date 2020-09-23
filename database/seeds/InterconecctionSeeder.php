<?php

use Illuminate\Database\Seeder;

class InterconecctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $interconnections = [
            1 => [
                'name' => 'Interconexión 1 (.208)',
                'connection_name' => 'asterisk',
                'connection_no_strict_name' => 'asterisk.nostrict'
            ],
            2 => [
                'name' => 'Interconexión 2 (.209)',
                'connection_name' => 'asterisk2',
                'connection_no_strict_name' => 'asterisk2.nostrict'
            ],
        ];

        foreach ($interconnections as $interconnection) {
            DB::table('interconnections')->insert(
                [
                    'name' => $interconnection['name'],
                    'connection_name' => $interconnection['connection_name'],
                    'connection_no_strict_name' => $interconnection['connection_no_strict_name']
                ]
            );
        }
    }
}
