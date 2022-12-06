<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreManyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->can('createMany', [Classroom::class, $this->school_id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employees.*.name' => ['required', 'string'],
            'employees.*.nik' => ['required', 'string'],
            'employees.*.school_id' => ['required', 'exists:schools,id'],
        ];
    }
}
