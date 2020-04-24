<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
            'code' => "required|unique:units,code,{$id}"
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Không được bỏ trống tên đơn vị',
            'code.required' => 'Không được bỏ trống mã đơn vị',
            'code.unique' => 'Mã đơn vị không được trùng',
        ];
    }
}
