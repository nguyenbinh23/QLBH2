<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class CustomerRequest extends FormRequest
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
            'name' => 'required',
            'email' => "required|unique:customers,email,{$id}",
            'address' => 'required',
            'phone' => 'required',
            'sorting' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập tên',
            'email.required' => 'Bạn cần nhập email',
            'email.unique' => 'Không được trùng email',
            'address.required' => 'Không được bỏ trống địa chỉ',
            'phone.required' => 'Không được bỏ trống số điện thoại',
            'sorting.required' => 'Hãy chọn loại khách hàng',
        ];
    }
}
