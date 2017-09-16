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
        ///
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
	        $table->unsignedInteger('parent_id')->default(0)->comment('上级ID:0表示最顶级');
	        $table->unsignedTinyInteger('level')->default(1)->comment('等级');
	        $table->rememberToken();
	        $table->timestamp('active_at')->nullable()->comment('激活时间');
            $table->timestamps();
        });
	    DB::statement("ALTER TABLE `{$usersTable}` comment '用户表'");

	    ///
	    $userCodesTable = 'user_codes';
	    Schema::create($userCodesTable, function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('create_uid')->comment('创建者ID');
	        $table->unsignedInteger('use_uid')->default(0)->comment('使用者ID，0表示未使用');
	        $table->char('code', 10)->comment('邀请码');
	        $table->timestamp('create_at')->comment('创建时间');
	        $table->timestamp('use_at')->nullable()->comment('使用时间');
        });
	    DB::statement("ALTER TABLE `{$userCodesTable}` comment '用户邀请码表'");
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
