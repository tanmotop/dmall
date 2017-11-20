<?php

use Illuminate\Database\Seeder;

class PvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pv')->insert([
            ['target_pv' => 2, 'percent' => 3, 'describe' => '月销售PV值达到2万'],
            ['target_pv' => 5, 'percent' => 5, 'describe' => '月销售PV值达到5万'],
            ['target_pv' => 8, 'percent' => 6, 'describe' => '月销售PV值达到8万'],
            ['target_pv' => 10, 'percent' => 8, 'describe' => '月销售PV值达到10万'],
            ['target_pv' => 18, 'percent' => 10, 'describe' => '月销售PV值达到18万'],
            ['target_pv' => 30, 'percent' => 15, 'describe' => '月销售PV值达到30万'],
            ['target_pv' => 50, 'percent' => 18, 'describe' => '月销售PV值达到50万'],
            ['target_pv' => 80, 'percent' => 20, 'describe' => '月销售PV值达到80万'],
        ]);
    }
}
