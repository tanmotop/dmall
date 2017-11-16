<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2017/11/14
 * Time: 14:31
 * Function:
 */

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'realname' => 'required',
            'phone' => 'required|regex:/^1[3,5,7,8][0-9]{9}$/',
            'wechat' => 'required',
            'new_password' => 'required_with:old_password',
            'confirm_password' => 'same:new_password'
        ];
    }

    public function messages()
    {
        return [
            'realname.required' => '请输入真实姓名',
            'phone.required' => '请填写手机号码',
            'phone.regex' => '请输入正确手机号码',
            'wechat.required' => '请输入微信号',
            'new_password.required_with' => '请输入新密码',
            'confirm_password.same' => '两次密码不一致',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, $this->formatErrors($validator));
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function formatErrors(Validator $validator)
    {
        $message = $validator->errors()->all();
        $result = [
            'code' => 10001,
            'msg' => current($message),
        ];

        return response($result, 422);
    }
}