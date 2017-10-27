<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('courier_id')->comment('快递公司id');
            $table->unsignedInteger('region_id')->comment('地区id');
            $table->double('norm_weight', 15)->comment('标准重量');
            $table->double('norm_price', 15)->comment('正常运费');
            $table->double('over_first_price')->comment('超重首重运费');
            $table->double('over_next_price')->comment('超重每公斤价格，单位x元/kg');
            $table->tinyInteger('faraway')->default(0)->comment('是否偏远地区0否1是');
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
        Schema::dropIfExists('freights');
    }
}
