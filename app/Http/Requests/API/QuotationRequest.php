<?php

namespace App\Http\Requests\API;

use Carbon\Carbon;
use App\Enums\Currency;
use Illuminate\Validation\Rule;
use App\Rules\ValidAgeListInRange;
use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
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
            'age' => ['required', 'string', 'regex:/^(\d+)(,\d+)*$/', new ValidAgeListInRange()],
            'currency_id' => ['required', 'string', Rule::enum(Currency::class)],
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'age.regex' => 'Age must be comma-separated numbers.',
        ];
    }

    public function getAges(): array
    {
        return array_map('intval', explode(',', $this->validated('age')));
    }

    public function getStartDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->validated('start_date'));
    }

    public function getEndDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->validated('end_date'));
    }
}
