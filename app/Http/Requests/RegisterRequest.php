<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Vous pouvez ajouter une logique d'autorisation si nécessaire
    }

    public function rules()
    {
        return [
            'login' => 'required|string|unique:users,login',
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/[a-z]/',      // Au moins une lettre minuscule
                'regex:/[A-Z]/',      // Au moins une lettre majuscule
                'regex:/[0-9]/',      // Au moins un chiffre
                'regex:/[@$!%*#?&]/', // Au moins un caractère spécial
                // 'confirmed',       // Le mot de passe doit être confirmé
            ],
            'client_id' => 'required|exists:clients,id',
            'photo' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Le champ login est obligatoire.',
            'login.unique' => 'Ce login est déjà utilisé.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 5 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
            'clientid.required' => 'Le champ clientid est obligatoire.',
            'clientid.exists' => 'Le client spécifié n\'existe pas.',
            'photo.required' => 'Le champ photo est obligatoire.',
        ];
    }
}
