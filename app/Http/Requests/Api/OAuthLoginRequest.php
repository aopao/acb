<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OAuthLoginRequest extends FormRequest
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
            'code' => 'required',//验证Code
        ];
    }

    /**错误信息提示
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'Code不存在',
        ];
    }
}
