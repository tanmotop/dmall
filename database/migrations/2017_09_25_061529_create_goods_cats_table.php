<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_cats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)->comment('商品分类名称');
            $table->unsignedInteger('sort')->default(50)->comment('商品排序，默认50,小在前，大在后');
            $table->tinyInteger('status')->default(0)->comment('分类状态：1显示、0隐藏');
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
        Schema::dropIfExists('goods_categories');
    }
}
