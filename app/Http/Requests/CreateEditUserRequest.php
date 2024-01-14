<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
class CreateEditUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required','email', 'max:255','regex:/^[a-zA-Z0-9._%+-]+@feyalegria\.org\.sv$/', Rule::unique('users','email')->ignore($this->user?->id)],
            'branch_id' => ['required','numeric','gt:0','exists:branches,id'],
            'roles_id' => ['required','array'],
            'roles_id.*' => ['numeric','gt:0','exists:roles,id'],
        ];
    }
    public function validatedUser(): array
    {
        return $this->only(['name','email','branch_id']);
    }
    public function validatedRolesIds(): array 
    {
        return $this->only('roles_id')['roles_id'];
    }
}
