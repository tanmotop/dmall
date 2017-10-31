<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceRefundLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_refund_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn')->unique()->comment('退款单号');
            $table->unsignedInteger('uid')->comment('用户ID');
            $table->string('realname')->comment('姓名');
            $table->double('money', 15, 2)->comment('退款金额');
            $table->double('money_pre', 15, 2)->comment('退款前金额');
            $table->double('money_after', 15, 2)->comment('退款后金额');
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
        Schema::dropIfExists('finance_refund_log');
    }
}
