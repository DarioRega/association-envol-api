<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('intervals')->insert([
            'ref' => 'one_time',
            'name' => 'Ponctuel',
        ]);
        DB::table('intervals')->insert([
            'ref' => 'month',
            'name' => 'Mensuel',
        ]);
        DB::table('intervals')->insert([
            'ref' => 'year',
            'name' => 'Annuel',
        ]);
    }
}
