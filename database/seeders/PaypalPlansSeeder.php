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
        if(config('env-variables.app_env') == 'production'){
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
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-9N345124SA099804FMCC2XMI',
                'name' => 'custom-32-month',
            ]);
        } else {
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-9GY7790675637152XMBW3UYY',
                'name' => 'main-20-month',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-71S056945V467502XMBW3U4Y',
                'name' => 'main-50-month',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-340567657T573930YMBW3VBI',
                'name' => 'main-100-month',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-4YM288930E670430SMBW3VFQ',
                'name' => 'main-20-year',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-34T70807PY002533JMBW3VKY',
                'name' => 'main-50-year',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-0EA89238KD503911AMBW3VPA',
                'name' => 'main-100-year',
            ]);
            DB::table('paypal_plans')->insert([
                'plan_id' => 'P-95P46002F0708125DMBW5O7I',
                'name' => 'custom-66-month',
            ]);
        }

    }
}
