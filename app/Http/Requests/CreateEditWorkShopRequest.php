<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CreateEditWorkShopRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255' ,'min:3', Rule::unique('work_shops','name')->ignore($this->workshop?->id)],
            'address' => ['required','min:10','string','max:5000'],
            'email' => ['required','email', Rule::unique('work_shops','email')->ignore($this->workshop?->id)],
            'telephone' => ['required','regex:/^(2|6|7|8)[0-9]{7}$/'],
            'district_id' => ['required','exists:districts,id'],
        ];
    }
}
