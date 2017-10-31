<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/10/26
 * Time: 14:45
 * 团队层级图
 */

namespace App\Admin\Controllers\Users;


use App\Models\User;
use App\Models\UserLevel;
use Carbon\Carbon;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class TreeController
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('团队层级图');
            $content->body($this->tree()->render());
        });
    }

    protected function tree()
    {
        $levels = (new UserLevel)->getIdNameArray();
        $tree = Admin::tree(User::class, function (Tree $tree) use ($levels) {
            $logo = "<i class=\"fa fa-user\"></i>";
            $tree->branch(function ($branch) use ($levels, $logo) {
                $date = Carbon::parse($branch['actived_at'])->toDateString();
                $level = $levels[$branch['level']];
                return "$logo 【ID:{$branch['id']}、{$branch['realname']}、{$date}、{$level}】";
            });
        });

        $tree->disableCreate();

        return $tree;
    }
}