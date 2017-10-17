<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn', 32)->comment('商品编号');
            $table->string('name', 64)->comment('商品名称');
            $table->string('short_name', 32)->comment('商品简称');
            $table->string('keywords', 128)->comment('关键词');
            $table->unsignedInteger('cat_id')->default(0)->comment('商品分类id');
            $table->unsignedInteger('sort')->default(50)->comment('商品排序，默认50,小在前，大在后');
            $table->tinyInteger('status')->default(0)->comment('商品状态：1上架、0下架、2售完');
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
        Schema::dropIfExists('goods');
    }
}
