<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PayConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pay_config')->insert([
            'bank_name' => '中国建设银行',
            'bank_card' => '6227001935560372889',
            'bank_user' => '林加强',
            'bank_address' => '福建省厦门市集美区集美支行',
            'alipay_name' => '大山2',
            'alipay_phone' => '13779975997',
            'alipay_user' => '林加强',
            'wechat_qr_code' => 'images/wechat_pay.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
