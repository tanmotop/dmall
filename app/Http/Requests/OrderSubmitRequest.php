<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name'     => 'required',
            'user_province' => 'required',
            'user_city'     => 'required',
            'user_area'     => 'required',
            'user_tel'      => 'required',
            // 'remarks'       => 'required',
            // 'freight'       => 'required',
            'total_price'   => 'required',
            // 'post_way'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_province.required' => '请选择省份',
            'user_city.required'     => '请选择城市',
            'user_area.required'     => '请选择地区',
            'user_name.required'     => '请输入收货人',
            'user_tel.required'      => '请输入手机号',
            'total_price.required'   => '订单有误，请刷新页面重试',
        ];
    }
}
