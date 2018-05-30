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
    var id = $(this).data('id');
    swal({ 
      title: '订单号:' + $(this).data('sn'),
      text: "快捷发货",
      type: "input", 
      showCancelButton: true, 
      closeOnConfirm: false, 
      animation: "slide-from-top", 
      confirmButtonText: '提交',
      cancelButtonText: '取消',
      inputPlaceholder: "请输入快递单号" 
    },
    function(inputValue){ 
        if (inputValue === false) return false; 
      
        if (inputValue === "") { 
            swal.showInputError("请输入快递单号！");
            return false 
        } 
        swal({
            title: "确认快递单号",
            text: inputValue,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "是的,没错!",
            cancelButtonText: '取消',
            closeOnConfirm: false
        },function(){
            $.ajax({
                url: '/admin/api/orders/deliver',
                type: 'POST',
                dataType: 'json',
                data: {
                    id:id,
                    postid:inputValue,
                    _token:LA.token
                },
                success: function (data) {
                    $.pjax.reload('#pjax-container');
                    
                    if (typeof data === 'object') {
                        if(data.status) {
                            swal(data.message, '', 'success');
                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                }
            });
        });
    });
});
SCRIPT;

    }

    public function render()
    {
        Admin::script($this->script());

        return <<<TAG
<a href="javascript:void(0);" data-id="{$this->id}" data-sn="{$this->sn}" class="grid-row-one-key">
    <i class="fa fa-paper-plane"></i>
</a>&nbsp;
TAG;

    }
}