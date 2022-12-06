<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;

class ClassroomStoreManyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->can('createMany', [Classroom::class, $this->classrooms[0]['school_id']]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'classrooms.*.class' => ['required', 'string'],
            'classrooms.*.name' => ['required', 'string'],
            'classrooms.*.year' => ['required', 'date_format:Y'],
            'classrooms.*.school_id' => ['required', 'exists:schools,id'],
        ];
    }
}
