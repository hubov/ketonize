<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:ingredients,name',
            'ingredient_category_id' => 'required|numeric',
            'protein' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
            'fat' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
            'carbohydrate' => 'required|regex:/^[0-9]+(\.[0-9]+)?$/',
            'kcal' => 'required|numeric',
            'unit_id' => 'required|numeric'
        ];
    }
}
