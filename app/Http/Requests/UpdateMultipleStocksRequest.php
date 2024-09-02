<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleStocksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'articles' => 'required|array|min:1',
            'articles.*.id' => 'required|integer',
            'articles.*.qteStock' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'articles.required' => 'Le tableau d\'articles est requis',
            'articles.min' => 'Le tableau doit contenir au moins un article',
            'articles.*.id.exists' => 'L\'ID de l\'article n\'existe pas dans la base de données',
            'articles.*.qteStock.min' => 'La quantité en stock doit être supérieure à zéro',
        ];
    }
}
