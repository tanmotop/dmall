<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmountChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('代理商id');
            $table->tinyInteger('type')->comment('金额变动类型：1在线充值2管理员充值');
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
        Schema::dropIfExists('amount_changes');
    }
}
