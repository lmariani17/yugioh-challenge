<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpace;
use App\Rules\Decimal;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
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
            'name' => ['required', new AlphaSpace],
            'description' => ['required', new AlphaSpace],
            'first_edition' => ['required', 'boolean'],
            'serial_code' => ['required', 'alpha_num'],
            'type' => ['required', Rule::in(['Monster', 'Magic', 'Trap'])],
            'attack' => ['integer'],
            'defense' => ['integer'],
            'star' => ['integer'],
            'amount' => ['required', new Decimal],
            'subtype_id' => ['required', 'exists:subtypes,id'],
            'image_id' => ['required', 'exists:images,id']
        ];
    }
}
