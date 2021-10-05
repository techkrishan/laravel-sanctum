<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class ResetPasswordOtpRequest extends FormRequest
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
            'email' => [
                'bail','required','string','email','max:255',
                Rule::exists((new User())->getTable(), 'email')
                    ->where('is_deleted', config('constants.boolean_false'))
                    ->where('is_active', config('constants.boolean_true'))
            ],
        ];
    }

    public function messages() {
        return [
            'email.exists' => __('validation.reset_password_otp_exists'),
        ];
    }
}
