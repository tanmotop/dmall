<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedInteger('attr_id')->comment('商品规格id');
            $table->double('price', 15, 2)->comment('购买价格');
            $table->unsignedInteger('count')->comment('购买数量');
            $table->unsignedInteger('pv')->comment('购买时pv');
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
        Schema::dropIfExists('orders_goods');
    }
}
