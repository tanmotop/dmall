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
	        $table->string('username', 50)->unique()->comment('用户名');
	        $table->string('password', 60)->comment('密码');
	        $table->string('realname', 10)->comment('真实姓名');
	        $table->string('avatar')->nullable()->comment('头像');
	        $table->string('id_card_num', 20)->comment('身份证号');
	        $table->string('wechat', 100)->comment('微信号');
	        $table->string('phone', 20)->comment('手机号');
            $table->string('email', 64)->comment('邮箱');
	        $table->unsignedTinyInteger('level')->comment('等级');
	        $table->rememberToken();
            $table->string('invitation_code', 10)->comment('激活码');
	        $table->timestamp('actived_at')->nullable()->comment('激活时间');
            $table->unsignedInteger('parent_id')->default(0)->comment('上级ID:0表示最顶级');
            $table->tinyInteger('status')->default(0)->comment('激活状态：1正常0未激活');
            $table->timestamps();
        });
	    DB::statement("ALTER TABLE `{$usersTable}` comment '用户表'");
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
