<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name');
            $table->string('bank_card');
            $table->string('bank_user');
            $table->string('bank_address');
            $table->string('alipay_name');
            $table->string('alipay_phone');
            $table->string('alipay_user');
            $table->string('wechat_qr_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_config');
    }
}
