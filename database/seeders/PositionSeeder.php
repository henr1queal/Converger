<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('positions')->insert([
            'name' => 'CEO',
        ]);

        DB::table('positions')->insert([
            'name' => 'Atendimento',
        ]);
    }
}
