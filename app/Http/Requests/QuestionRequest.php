<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Lookup;

class QuestionRequest extends FormRequest
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
        $required = 'required';
        if($requestMethod === 'PUT') {
            $required = 'nullable';
        }

        return [
            'category_id'   => [
                'bail',
                $required,
                'numeric',
                Rule::exists((new Lookup())->getTable(), 'id')
                    ->where('lookup_type', config('lookups.lookup_type.question_type.slug'))
                    ->where('is_deleted', config('constants.boolean_false'))
                    ->where('is_active', config('constants.boolean_true')),
            ], 
            'question'      => 'bail|'.$required.'|string|max:1500',
            'is_active'     => 'bail|nullable|boolean',
        ];
    }
}
