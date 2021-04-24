<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaypalPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-10152517AX348134KMB7PHWQ',
            'name' => 'main-20-month',
        ]);
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-6LA62047WM053331TMB7PHRQ',
            'name' => 'main-50-month',
        ]);
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-5Y420283WX222445HMB7PHJI',
            'name' => 'main-100-month',
        ]);
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-38K518407K668393AMB7PHAY',
            'name' => 'main-20-year',
        ]);
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-2MM32142TG506123DMB7PG3Y',
            'name' => 'main-50-year',
        ]);
        DB::table('paypal_plans')->insert([
            'plan_id' => 'P-7N809603FM8652331MB7PGWI',
            'name' => 'main-100-year',
        ]);
    }
}
