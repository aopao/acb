<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'mobile' => [
                'required',
                'regex:/^1[34578][0-9]\d{4,8}|(\w)+(\.\w+)*@(\w)+((\.\w+)+)|[0-9a-zA-Z_]+$/',//验证为手机号，邮箱，或帐号
            ],
            'password' => 'required|between:3,18',//验证密码
        ];
    }

    /**错误信息提示
     *
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required' => '手机号码不能为空',
            'mobile.regex' => '手机号码不合法',
            'password.required' => '密码不能为空',
            'password.between' => '密码最少6位',
        ];
    }
}
