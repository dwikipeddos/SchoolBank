<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class StudentStoreManyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->can('createMany', Student::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'students' => ['array:nis,classroom_id,name,email'],
            'students.*.nis' => ['string', 'min:5'],
            'students.*.classroom_id' => ['exists:classrooms,id'],
            'students.*.name' => ['string', 'min:3'],
            'students.*.email' => ['email'],
        ];
    }
}
