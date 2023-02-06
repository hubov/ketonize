<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateRecipeRequest extends StoreRecipeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['recipe.name'] = [
            'required',
            Rule::unique('recipes', 'name')->ignore($this->slug, 'slug')
        ];

        return $rules;
    }
}
