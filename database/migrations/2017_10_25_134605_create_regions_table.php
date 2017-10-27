<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32)->comment('地区名称');
            $table->unsignedInteger('parent_id')->default(0)->comment('上级ID:0表示最顶级');
            $table->unsignedInteger('sort')->default(50)->comment('排序，默认50,小在前，大在后');
            $table->unsignedInteger('level')->default(0)->comment('地区等级0顶级1一级1二级2三级');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
