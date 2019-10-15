<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
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
                'name' => 'VoIP',
                'description' => 'Voz sobre protocolo de internet.'
            ],
            2 => [
                'name' => 'ITFS',
                'description' => 'Numeración internacional gratuita'
            ],
            3 => [
                'name' => 'Fijo',
                'description' => 'Numeración telefónica fija '
            ],
            4 => [
                'name' => 'Movil',
                'description' => 'Numeración telefónica movil'
            ]
        ];

        foreach ($types as $type) {
            DB::table('types')->insert([
                'name' => $type['name'],
                'description' => $type['description']
            ]);
        }
        
    }
}
