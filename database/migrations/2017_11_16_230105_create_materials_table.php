<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 32)->comment('资料标题');
            $table->unsignedInteger('type_id')->comment('分类id');
            $table->text('content')->comment('资料内容');
            $table->string('attach', 256)->comment('附件资料');
            $table->unsignedInteger('sort')->default(50)->comment('排序');
            $table->tinyInteger('status')->comment('状态，0不显示 1显示');
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
        Schema::dropIfExists('materials');
    }
}
