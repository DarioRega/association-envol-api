<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_amounts')->insert([
            'amount' => 2000,
        ]);
        DB::table('main_amounts')->insert([
            'amount' => 5000,
        ]);
        DB::table('main_amounts')->insert([
            'amount' => 10000,
        ]);

    }
}
