<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/20
 * Time: 15:25
 * Function:
 */

namespace App\Admin\Controllers\Data;


use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;

class TeamsController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('团队消费');
            $content->description('排行');
        });
    }
}