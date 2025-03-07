<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JsonRequest extends FormRequest
{
    public function all($keys = null): array {
        if(empty($keys)){
            return parent::json()->all();
        }

        return collect(parent::json()->all())->only($keys)->toArray();
    }

    public function failedValidation(Validator $validator): void {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
