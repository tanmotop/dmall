<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/21
 * Time: 14:30
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    protected $table = 'user_bonus';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUserIdAttribute($id)
    {
        return substr(1000000 + $id, 1);
    }
}