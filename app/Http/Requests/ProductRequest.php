<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        // $id = $this->id;
        return [
            'files.*' => 'max:5120|mimes:jpg,jpeg,png,bmp,tiff,gif',
            'product_image' => 'max:5120|mimes:jpg,jpeg,png,bmp,tiff,gif',
            'name' => 'required',
            'code' => "required|unique:products,code," . $this->id,
            'description' => 'max:255',
            'unit_id' => 'required',
            'category_id' => 'required',
            'quantity' => 'required|numeric|between:1,1000000000',
        ];
    }
    public function messages()
    {


        return [
            'product_image.mimes' => 'Ảnh đại diện phải có đuôi jpg,jpeg,png,bmp,tiff',
            'files.*.max' => 'max',
            'files.*.mimes' => 'mimes',
            'product_image.max' => 'Ảnh đại diện phải có kích thước nhỏ hơn 5MB',
            'name.required' => 'Không được bỏ trống tên hàng hóa',
            'code.required' => 'Không được bỏ trống mã hàng hóa',
            'code.unique' => 'Không được trùng mã hàng hóa',
            'description.max' => 'Mô tả không được quá 255 kí tự',
            'unit_id.required' => 'Hãy chọn đơn vị sản phẩm',
            'category_id.required' => 'Hãy chọn loại sản phẩm',
            'quantity.required' => 'Không được bỏ trống số lượng hàng hóa',
            'quantity.between' => 'Số lượng không phù hợp',
            'quantity.numeric' => 'Số lượng chỉ được nhập số'
        ];
    }
}
