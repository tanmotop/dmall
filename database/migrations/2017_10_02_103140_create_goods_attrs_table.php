<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)->comment('属性名称');
            $table->unsignedInteger('goods_id')->comment('商品id');
            $table->unsignedInteger('sort')->default(50)->comment('商品排序，默认50,小在前，大在后');
            $table->unsignedInteger('stock')->comment('库存');
            $table->unsignedInteger('weight')->comment('重量，单位：克');
            $table->unsignedInteger('pv')->comment('pv值');
            $table->double('price', 15, 2)->comment('价格');
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
        Schema::dropIfExists('goods_attrs');
    }
}
