<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceRechargeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_recharge_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn')->unique()->comment('充值单号');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->string('realname', 10)->comment('姓名');
            $table->double('money', 15, 2)->comment('充值金额');
            $table->double('money_pre', 15, 2)->comment('充值前金额');
            $table->double('money_after', 15, 2)->comment('充值后金额');
            $table->unsignedTinyInteger('status')->default(1)->comment('充值状态0失败1成功');
            $table->unsignedTinyInteger('way')->default(1)->comment('充值方式1管理员充值2在线充值');
            $table->string('describe')->comment('说明');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('finance_recharge_log');
    }
}
