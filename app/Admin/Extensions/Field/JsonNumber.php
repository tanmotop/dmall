<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/6
 * Time: 15:56
 * Function:
 */

namespace App\Admin\Extensions\Field;


use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class JsonNumber extends Field
{
    use Field\PlainInput;

    protected $view = 'admin::form.json-number';

    protected static $js = [
        '/vendor/laravel-admin/number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $labels = (array) $this->label();
        $list = json_decode($this->value(), true);

        ///
        $this->script = '';
        foreach ($labels as $k => $v) {
            $this->script .= <<<EOT

$('.{$k}:not(.initialized)')
    .addClass('initialized')
    .bootstrapNumber({
        upClass: 'success',
        downClass: 'primary',
        center: true
    });

EOT;
        }

        ///
        Admin::script($this->script);

        return view($this->view, [
            'list' => $list,
            'labels' => $labels,
        ]);
    }
}