<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'amount' => '20',
        ]);
        DB::table('products')->insert([
            'amount' => '50',
        ]);
        DB::table('products')->insert([
            'amount' => '100',
        ]);
    }
}
