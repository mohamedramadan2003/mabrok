<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
    $user = $this->user();

    if (!$user) {
        return [];
    }

    return [
        'name' => ['required', 'string', 'max:100', 'min:2'],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id),
        ],
        'phone' => [
            'nullable',
            'string',
            'max:20',
            Rule::unique('users')->ignore($user->id),
        ],
    ];
}
}
