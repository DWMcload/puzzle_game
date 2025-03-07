<?php

use App\Http\Requests\JsonRequest;

class CreatePuzzleRequest extends JsonRequest
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
            'user_id' => 'required|integer', //|exists:users,id
            /* In a system with user handling, this validation would be extended with the part-string above */
        ];
    }
}
