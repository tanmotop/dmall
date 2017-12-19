<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/12/19
 * Time: 15:09
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    protected $primaryKey = 'key';
}