<?php

namespace App\Http\Requests;

use App\Traits\LoginThrottle;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    use LoginThrottle;
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }

    public function throttleKey()
    {
        return $this->email . "-" . $this->ip();
    }
}
