<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('代理商id');
            $table->string('sn', 32)->comment('订单编号');
            $table->string('user_name', 16)->comment('收货人姓名');
            $table->string('user_phone', 20)->comment('收货人电话');
            $table->string('user_province', 32)->comment('收货人省份');
            $table->string('user_city', 32)->comment('收货人城市');
            $table->string('user_area', 32)->comment('收货人地区');
            $table->string('user_address', 128)->comment('收货人详细地址');
            $table->string('remarks', 256)->nullable()->comment('用户备注');
            $table->double('freight', 15, 2)->comment('运费');
            $table->double('total_price', 15, 2)->comment('总价');
            $table->double('total_pv', 15, 2)->commnet('订单总pv');
            $table->unsignedTinyInteger('post_way')->comment('配送方式1快递配送2到店自提');
            $table->string('postid')->nullable()->comment('快递单号');
            $table->timestamp('canceled_at')->nullable()->comment('订单取消时间');
            $table->timestamp('completed_at')->nullable()->comment('订单完成时间');
            $table->tinyInteger('status')->default(0)->comment('订单状态：0:未发货; 1:已发货; 2:交易完成; 3已取消');
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
        Schema::dropIfExists('orders');
    }
}
