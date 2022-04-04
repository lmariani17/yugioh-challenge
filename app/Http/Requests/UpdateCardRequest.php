<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardRequest extends FormRequest
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
            'name' => [new AlphaSpace],
            'description' => [new AlphaSpace],
            'first_edition' => ['boolean'],
            'serial_code' => ['alpha_num'],
            'type' => [Rule::in(['Monster', 'Magic', 'Trap'])],
            'attack' => ['integer'],
            'defense' => ['integer'],
            'star' => ['integer'],
            'amount' => [new Decimal],
            'subtype_id' => ['exists:subtypes,id'],
            'image_id' => ['exists:images,id']
        ];
    }
}
