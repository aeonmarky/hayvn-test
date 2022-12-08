<?php

namespace App\Http\Requests;

use App\Rules\AddAggregatedMessagesDestinationDuplicateRule;
use App\Rules\AddAggregatedMessagesStructureRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class AddAggregatedMessagesRequest extends FormRequest
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
            'batches' => [
                // check the structure of the payload
                new AddAggregatedMessagesStructureRule(),
                // check duplication
                new AddAggregatedMessagesDestinationDuplicateRule()
            ]
        ];
    }

    /**
     * Custom validation response payload
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                response()->json([
                    'errorCode' => 'FAILED_TO_PROCESS_BATCH',
                    // being truthful with the requirements with one liner error message
                    'message' => join(". ", array_values($errors['batches']))
                ], 500)
            );
        }

        parent::failedValidation($validator);
    }
}
