<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Gérer l'autorisation avec les Policies dans les contrôleurs
    }

    public function rules()
    {
        return [
            'libelle' => 'required|string|max:255|unique:articles,libelle',
            'prix' => 'required|numeric|min:0',
            'qteStock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.max' => 'Le libellé ne doit pas dépasser 255 caractères.',
            'libelle.unique' => 'Ce libellé existe déjà.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix doit',
            'prix.required' => 'Le prix est obligatoire.',
            'qteStock.required' => 'La quantité en stock est obligatoire.',
        ];
    }
}
