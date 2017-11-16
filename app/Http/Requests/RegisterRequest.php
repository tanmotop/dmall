<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'invitation_code' => 'required',
            'username' => 'required|unique:users|regex:/^[a-zA-Z0-9]{3,16}$/',
            'password' => 'required',
            'repasswd' => 'required|same:password',
            'realname' => 'required',
            'id_card_num' => 'required|regex:/^[a-zA-Z0-9]{18}$/',
            'email' => 'required|email|unique:users',
            'wechat' => 'required',
            'phone' => 'required|regex:/^1[3,5,7,8][0-9]{9}$/',
            //
        ];
    }

    public function messages()
    {
        return [
            'invitation_code.required' => '请输入邀请码',
            'username.required'    => '请输入用户名',
            'username.unique'      => '用户名已存在',
            'username.regex'       => '用户名只能包含3-16位的字母或数字',
            'password.required'    => '请输入密码',
            'repasswd.required'    => '请输入确认密码',
            'repasswd.same'        => '两次密码不一致',
            'realname.required'    => '请输入真实姓名',
            'id_card_num.required' => '请输入身份证号',
            'id_card_num.regex'    => '身份证号格式有误',
            'email.required'       => '请输入邮箱',
            'email.email'          => '邮箱格式有误',
            'email.unique'         => '邮箱已存在',
            'wechat.required'      => '请输入微信号',
            'phone.required'       => '请输入手机号码', 
            'phone.regex'          => '手机号码格式有误',
        ];
    }
}
