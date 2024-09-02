<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'qteStock' => 'required|numeric|min:1', // La quantité doit être numérique et positive
        ];
    }
}
