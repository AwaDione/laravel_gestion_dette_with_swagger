<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true; // Ajuste en fonction des besoins de sécurité
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'login')
            ],
            'password' => [
                'required',
                'string',
                'min:5', // Minimum 5 caractères
                'regex:/[a-z]/', // Au moins une lettre minuscule
                'regex:/[A-Z]/', // Au moins une lettre majuscule
                'regex:/[0-9]/', // Au moins un chiffre
                'regex:/[@$!%*?&]/' // Au moins un caractère spécial
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'boutiquier'])
            ],
        ];
    }

    public function messages()
    {
        return [
            'login.unique' => 'Ce login est déjà utilisé.',
            'password.regex' => 'Le mot de passe doit contenir des lettres majuscules, minuscules, des chiffres et des caractères spéciaux.',
        ];
    }
}
