<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ///
        $userCodesTable = 'user_codes';
        Schema::create($userCodesTable, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('create_uid')->comment('创建者ID');
            $table->unsignedInteger('use_uid')->default(0)->comment('使用者ID，0表示未使用');
            $table->string('code', 10)->comment('邀请码');
            $table->timestamp('expired_at')->comment('过期时间');
            $table->timestamp('used_at')->nullable()->comment('使用时间');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `user_codes` CHANGE `create_uid` `create_uid` INT(6)  UNSIGNED  ZEROFILL  NULL  DEFAULT NULL');
        DB::statement('ALTER TABLE `user_codes` CHANGE `use_uid` `use_uid` INT(6)  UNSIGNED  ZEROFILL  NULL  DEFAULT NULL');
        DB::statement("ALTER TABLE `{$userCodesTable}` comment '用户邀请码表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('user_codes');
    }
}
