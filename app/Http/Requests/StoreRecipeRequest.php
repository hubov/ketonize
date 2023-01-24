<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
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
            'name' => 'required|unique:recipes,name',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|numeric|min:1',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|numeric|min:1',
            'description' => 'required|string|min:1',
            'preparation_time' => 'required|numeric|min:0',
            'cooking_time' => 'required|numeric|min:0',
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|numeric|min:1'
        ];
    }
}
