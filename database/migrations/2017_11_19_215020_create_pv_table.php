<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('target_pv')->comment('目标值');
            $table->unsignedInteger('percent')->comment('百分比');
            $table->string('describe')->comment('说明');
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
        Schema::dropIfExists('pv');
    }
}
