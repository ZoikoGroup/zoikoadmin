<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// ...
class StoreProductAttributeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'name' => [
                'required',
                // 'unique:product_attributes,name',
                //Rule::unique('product_attributes', 'name')->where('product_id', $this->input('product_id')),
            ],

        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Attribute already exists!',
        ];
    }

}
