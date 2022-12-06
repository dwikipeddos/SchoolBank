<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()?->can('edit', $this->student);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nis' => ['string', 'min:5'],
            'classroom_id' => ['exists:classrooms,id'],
            'name' => ['string', 'min:3'],
            'email' => ['email'],
        ];
    }
}
