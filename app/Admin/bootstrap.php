<?php

use App\Admin\Extensions\WangEditor;
use App\Admin\Extensions\Field\JsonNumber;
use Encore\Admin\Form;
use Encore\Admin\Grid\Column;
use App\Admin\Extensions\ExpandRow;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

app('view')->prependNamespace('admin', resource_path('admin-views'));

Form::extend('editor', WangEditor::class);
Form::extend('json_number', JsonNumber::class);

Column::extend('expand', ExpandRow::class);
