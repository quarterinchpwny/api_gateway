<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginAuthenticationRequest extends FormRequest

{

    public function authorize()
    {
        return true;
    }

    public function rules()

    {

        return [
            'email' => 'string | required | email:rfc,dns',
            'password' => 'string | required | min:8 ',
        ];
    }
}
