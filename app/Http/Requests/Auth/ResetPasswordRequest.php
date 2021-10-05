<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'otp' => [
                'bail',
                'required',
                'min:'.config('constants.otp_length'),
                'max:'. config('constants.otp_length'),
            ],
            'password' => 'bail|required|string|min:6|max:30',
        ];
    }
}
