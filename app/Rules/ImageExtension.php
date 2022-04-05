<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageExtension implements Rule
{
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, self::ALLOWED_EXTENSIONS);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must jpg, jpeg or png.';
    }
}
