<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category_name' => 'required',
            'category_code' => "required|unique:categories,category_code,{$id}"
        ];
    }
    public function messages()
    {
        return [
            'category_name.required' => 'Không được bỏ trống tên thể loại',
            'category_code.required' => 'Không được bỏ trống mã thể loại',
            'category_code.unique' => 'Mã thể loại không được trùng',
        ];
    }
}
