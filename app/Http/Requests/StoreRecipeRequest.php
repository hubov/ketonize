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
            'recipe.name' => 'required|unique:recipes,name',
            'recipe.image' => 'mimetypes:image/jpeg|max:10240',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'required|array|min:2|max:2',
            'ingredients.*.id' => 'required|numeric|min:1',
            'ingredients.*.quantity' => 'required|numeric|min:1',
            'recipe.description' => 'required|string|min:1',
            'recipe.preparation_time' => 'required|numeric|min:0',
            'recipe.cooking_time' => 'required|numeric|min:0',
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|numeric|min:1'
        ];
    }
}
