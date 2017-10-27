<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level')->comment('代理商等级，越小等级越高');
            $table->string('name', 32)->comment('等级名称');
            $table->string('upgrade_condition', 256)->comment('升级条件说明');
            $table->double('first_amount', 15, 2)->default(0)->comment('首充金额');
            $table->double('second_amount', 15, 2)->default(0)->comment('再充金额');
            $table->tinyInteger('upgrade_way')->default(1)->comment('升级方式：1手动2自动');
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
        Schema::dropIfExists('levels');
    }
}
