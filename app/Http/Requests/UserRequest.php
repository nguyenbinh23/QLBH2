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
        $id = $this->id;
        return [
            'name' => 'required|max:60',
            'email' => "required|max:60|unique:users,email,{$id}",
            'password' => 'required|max:60|min:8',
            'password_confirm' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập họ và tên',
            'name.max' => 'Họ và tên quá dài, tối đa 60 kí tự',
            'email.required' => 'Bạn cần nhập email',
            'email.unique' => 'Đã có người sử dụng email này, vui lòng sử dụng email khác',
            'email.max' => 'Email quá dài, tối đa 60 kí tự',
            'password.required' => 'Bạn cần nhập mật khẩu',
            'password.max' => 'Mật khẩu quá dài, tối đa 60 kí tự',
            'password.min' => 'Mật khẩu quá ngắn, tối thiểu 8 kí tự',
            'password_confirm.required' => 'Bạn cần nhập lại mật khẩu'
        ];
    }
}
