<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 32)->comment('用户名');
            $table->string('phone', 16)->comment('联系方式');
            $table->string('wechat', 32)->comment('微信号');
            $table->string('order_sn', 64)->nullable()->comment('订单编号');
            $table->text('msg')->comment('留言内容');
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
        Schema::dropIfExists('messages');
    }
}
