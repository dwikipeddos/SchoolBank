<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreManyRequest extends FormRequest
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
            'transactions.*.user_ids' => ['exists:users,id'],
            'transactions.*.amounts' => ['exists:users,id'],
            // 'user_ids' => ['array', 'required'],
            // 'user_ids.*' => ['exists:users,id'],
            // 'amounts' => ['array', 'required'],
            // 'amounts.*' => ['numeric'],
        ];
    }
}
