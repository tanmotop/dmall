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

class OneKeyDeliver
{
    protected $id;

    protected $sn;

    public function __construct($id, $sn)
    {
        $this->id = $id;
        $this->sn = $sn;
    }

    public function __toString()
    {
        return $this->render();
    }

    protected function script()
    {
        return <<<SCRIPT
$('.grid-row-one-key').on('click', function () {
    // Your code.
    //console.log($(this).data('id'));
    //console.log($(this).data('sn'));
    swal({
      title: '订单号:' + $(this).data('sn'),
      text: '请输入快递单号',
      type: 'input',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: '提交',
      cancelButtonText: '取消',
      showLoaderOnConfirm: true,
      allowOutsideClick: true
    }, function(value) {
    });
});
SCRIPT;

    }

    protected function render()
    {
        Admin::script($this->script());

        return <<<TAG
<a href="javascript:void(0);" data-id="{$this->id}" data-sn="{$this->sn}" class="grid-row-one-key">
    <i class="fa fa-paper-plane"></i>
</a>
TAG;

    }
}