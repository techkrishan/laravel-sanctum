<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Lookup;

class UserRequest extends FormRequest
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
        $requestMethod = $this->getMethod();
        $required = 'bail|required';
        if($requestMethod === 'PUT') {
            $required = 'bail|nullable';
        }

        return [
            'user_type_id' => ['bail','nullable','numeric',
                Rule::exists((new Lookup())->getTable(), 'id')
                    ->where('lookup_type', config('lookups.lookup_type.user_type.slug'))
                    ->where('is_deleted', config('constants.boolean_false'))
                    ->where('is_active', config('constants.boolean_true'))
            ],
            'first_name'    => $required.'|string|max:100',
            'last_name'     => $required.'|string|max:100',
            'email'         => $required.'|string|email|unique:users,email',
            'password'      => $required.'|string|min:6|max:30',
            'mobile'        => 'bail|nullable|min:10|max:15',
        ];
    }
}
