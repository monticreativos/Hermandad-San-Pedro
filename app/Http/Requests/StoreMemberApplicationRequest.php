<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $emptyToNull = static fn (mixed $v): mixed => $v === '' ? null : $v;

        $this->merge([
            'phone' => $emptyToNull($this->phone),
            'birth_date' => $emptyToNull($this->birth_date),
            'address' => $emptyToNull($this->address),
            'message' => $emptyToNull($this->message),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
