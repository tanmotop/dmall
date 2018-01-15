<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/13
 * Time: 17:25
 * Function:
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|regex:/^1[3,5,7,8][0-9]{9}$/',
            'province' => 'required|regex:/^[1-9]\d*$/',
            'city' => 'required|regex:/^[1-9]\d*$/',
            'area' => 'required|regex:/^[1-9]\d*$/',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '请输入姓名',
            'phone.required'       => '请输入手机号码',
            'phone.regex'          => '手机号码格式有误',
            'province.required' => '请选择省份',
            'province.regex' => '请选择省份',
            'city.required' => '请选择城市',
            'city.regex' => '请选择城市',
            'area.required' => '请选择地区',
            'area.regex' => '请选择地区',
            'address' => '请输入详细地址'
        ];
    }
}