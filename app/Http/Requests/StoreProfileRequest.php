<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'diet_type' => 'required|integer|exists:diets,id',
            'diet_target' => 'required|integer|min:1|max:3',
            'meals_count' => 'required|integer|min:3|max:5',
            'gender' => 'required|integer|min:1|max:2',
            'birthday' => 'required|date',
            'weight' => 'required|integer|min:45|max:250',
            'height' => 'required|integer|min:120|max:230',
            'target_weight' => 'required|integer|min:45|max:250',
            'basic_activity' => 'required|integer|min:1|max:4',
            'sport_activity' => 'required|integer|min:1|max:4',
        ];
    }
}
