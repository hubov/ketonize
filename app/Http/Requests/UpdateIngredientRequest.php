<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateIngredientRequest extends StoreIngredientRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['name'] = [
            'required',
            Rule::unique('ingredients')->ignore($this->id)
        ];

        return $rules;
    }
}
