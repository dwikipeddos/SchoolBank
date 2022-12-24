<?php

namespace App\Http\Requests;

use App\Enums\TransactionTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TotalReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['sometimes', new Enum(TransactionTypes::class)],
            'start_date' => ['prohibits:today', 'date', 'after:today'],
            'end_date' => ['prohibits:today', 'date', 'after:start_date'],
            'today' => ['boolean', 'prohibits:start_date,end_date'],
        ];
    }
}
