<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	$usersTable = 'users';
        Schema::create($usersTable, function (Blueprint $table) {
            $table->increments('id');
	        $table->string('username')->comment('用户名');
	        $table->string('password')->comment('密码');
	        $table->string('realname')->comment('真实姓名');
	        $table->string('id_card_num')->comment('身份证号');
	        $table->string('wechat')->comment('微信号');
	        $table->string('phone')->comment('手机号');
	        $table->unsignedInteger('parent_id')->default(0)->comment('上级ID:0表示最顶级');
	        $table->unsignedTinyInteger('level')->default(1)->comment('等级');
	        $table->rememberToken();
            $table->timestamps();
        });

	    DB::statement("ALTER TABLE `$usersTable` comment '用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
