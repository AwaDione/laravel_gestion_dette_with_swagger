<?php

namespace App\Services;

use Illuminate\Http\Request;

interface ArticleServiceInterface
{
    public function all();

    public function find($id);

    public function findByLibelle($libelle);

    public function create(array $data);

    public function update($id, $quantity);

    public function bulkUpdateStock(array $articles);

    public function filterByStock(Request $request);
}
