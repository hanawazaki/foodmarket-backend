<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
            'name' => 'required|max:255',
            'picture_path' => 'required|image',
            'description' => 'required',
            'ingredient' => 'required',
            'price' => 'required|integer',
            'rate' => 'required|integer',
            'types' => '',
        ];
    }
}
