<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
    public function rules(): array {
    return [
        'company_id' => ['nullable','exists:companies,id'],
        'first_name' => ['required','string','max:255'],
        'last_name'  => ['required','string','max:255'],
        'email'      => ['nullable','email','max:255','unique:contacts,email'],
        'phone'      => ['nullable','string','max:255'],
        'position'   => ['nullable','string','max:255'],
        'notes'      => ['nullable','string'],
    ];
}
}
