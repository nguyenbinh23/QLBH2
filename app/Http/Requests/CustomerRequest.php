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
            'sorting' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn cần nhập tên',
            'sorting.required' => 'Hãy chọn loại khách hàng',
        ];
    }
}
