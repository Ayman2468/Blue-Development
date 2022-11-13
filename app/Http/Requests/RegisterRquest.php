<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRquest extends FormRequest
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
        $userid = request('id');
        return [
            //
            'name' => "required",
            'phone' => "required|unique:users,phone,$userid",
            'password' => "required|max:50|min:8|confirmed"
        ];
    }
}
