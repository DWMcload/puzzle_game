<?php

namespace App\Http\Requests;

class SubmitWordRequest extends JsonRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'puzzle_id' => 'required|integer|exists:puzzles,id',
            'word' => 'required|string',
        ];
    }
}
