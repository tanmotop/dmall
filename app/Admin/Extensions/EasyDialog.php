<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/2
 * Time: 16:49
 * Function: 一键发货
 */

namespace App\Admin\Extensions;


use Encore\Admin\Admin;

class EasyDialog
{
    protected $title;

    protected $content;

    public function __construct($title, $content)
    {
        $this->title   = $title;
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->render();
    }

    protected function script()
    {
        return <<<SCRIPT
$('.grid-dialog').on('click', function () {
    var title = $(this).data('title');
    var content = $(this).data('content');
    swal({
        title: title, 
        text: content, 
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定", 
    });
});
SCRIPT;

    }

    protected function render()
    {
        Admin::script($this->script());

        return <<<TAG
<a href="javascript:void(0);" data-title="{$this->title}" data-content="{$this->content}" class="grid-dialog">
    <i class="fa fa-paper-plane"></i>
</a>
TAG;

    }
}