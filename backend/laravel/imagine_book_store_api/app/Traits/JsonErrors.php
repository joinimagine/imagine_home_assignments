<?php


namespace App\Traits;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait JsonErrors
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()
                ->json(
                    [
                        'success' => false,
                        'data' => null,
                        'errors' => $validator->errors()
                    ]
                    , 422)
        );
    }
}
