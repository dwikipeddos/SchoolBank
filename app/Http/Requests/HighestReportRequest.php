<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HighestReportRequest extends FormRequest
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
            'school_id' => ['exists:schools,id'],
            'classroom_id' => ['exists:classrooms,id'],
            'limit' => ['integer', 'max:100'],
        ];
    }
}
