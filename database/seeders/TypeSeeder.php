<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            'name' => 'Rapports',
        ]);
        DB::table('types')->insert([
            'name' => 'Nationalités',
        ]);
        DB::table('types')->insert([
            'name' => 'Comptes',
        ]);
        DB::table('types')->insert([
            'name' => 'Formations',
        ]);

        DB::table('types')->insert([
            'name' => 'Commons',
        ]);

    }
}
