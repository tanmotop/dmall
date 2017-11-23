<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->double('level_money', 15, 2)->comment('级别差价收入');
            $table->double('invite_money', 15, 2)->comment('邀代奖金');
            $table->double('retail_money', 15, 2)->comment('零售利润');
            $table->unsignedInteger('personal_pv')->comment('个人PV');
            $table->unsignedInteger('teams_pv')->comment('团队PV');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bonus');
    }
}
