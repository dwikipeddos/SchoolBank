<?php

namespace App\Http\Requests;

use App\Models\Classroom;
use Illuminate\Foundation\Http\FormRequest;

class ClassroomStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->can('create', [Classroom::class, $this->school_id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'class' => ['required', 'string'],
            'name' => ['required', 'string'],
            'year' => ['required', 'date_format:Y'],
            'school_id' => ['required', 'exists:schools,id'],
        ];
    }
}
