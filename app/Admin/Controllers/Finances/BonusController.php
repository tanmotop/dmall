<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 14:46
 * Function:
 */

namespace App\Admin\Controllers\Finances;


use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;

class BonusController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('奖金');
            $content->description('查询');
        });
    }
}