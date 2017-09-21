<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/9/21
 * Time: 21:37
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'realname', 'avatar', 'id_card_num', 'wechat', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}