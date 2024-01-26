<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CreateEditRepairRequest extends FormRequest
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
            'car_id' => ['required', 'exists:cars,id','integer','gt:0'],
            'repair_status_id' => [Rule::requiredIf($this->repair == null), 'exists:repair_statuses,id','integer','gt:0'],
            'work_shop_id' => ['required', 'exists:work_shops,id','integer','gt:0'],
            'repair_date' => ['required', 'date'],
            'details' => ['required', 'array','min:1'],
            'details.*.name' => 'required|string|min:4',
            'details.*.description' => 'required|string|max:5000',
            'details.*.price' => 'required|numeric|min:0',

        ];
    }
    public function validatedRepair()
    {
        return $this->only('car_id','repair_status_id','work_shop_id');
    }
    
    public function sumPrices()
    {
        $details = $this->validated()['details'];

        $total = 0;

        foreach ($details as $detail) {
            $total += $detail['price'];
        }

        return [
            'total' => $total
        ];
    }
}
