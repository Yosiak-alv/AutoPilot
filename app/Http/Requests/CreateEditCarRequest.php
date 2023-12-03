<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEditCarRequest extends FormRequest
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
            'plates' => ['required','string','min:7','regex:/^[A-Z0-9]{7}$/',Rule::unique('cars','plates')->ignore($this->car)],
            'VIN' => ['required','string','min:17','regex:/\b[(A-H|J-N|P|R-Z|0-9)]{17}\b/',Rule::unique('cars','VIN')->ignore($this->car)],
            'current_mileage' => ['required','numeric','gt:0','min:1000'],
            'year' => ['required','numeric','gt:1990','min:1990','max:2024'],
            'model_id' => ['required','numeric','exists:models,id'],
            'branch_id' => ['required','numeric','exists:branches,id'],
        ];
    }
}
