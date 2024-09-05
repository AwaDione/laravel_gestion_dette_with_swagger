<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\Request;
use Exception;

class ArticleServiceImpl implements ArticleServiceInterface
{
    // Récupération de tous les articles
    public function all()
    {
        return Article::all();
    }

    // Recherche d'un article par ID
    public function find($id)
    {
        return Article::find($id);
    }

    // Recherche d'un article par libellé
    public function findByLibelle($libelle)
    {
        return Article::where('libelle', $libelle)->first();
    }

    // Création d'un nouvel article
    public function create(array $data)
    {
        return Article::create($data);
    }

    // Mise à jour de la quantité en stock par ID
    public function update($id, $quantity)
    {
        $article = $this->find($id);

        if (!$article) {
            return null; // Gérer cette condition dans le contrôleur
        }

        $article->qteStock = $quantity;
        $article->save();

        return $article;
    }

    // Mise à jour en lot des quantités en stock
    public function bulkUpdateStock(array $articles)
    {
        $updatedArticles = [];
        $notFoundArticles = [];

        foreach ($articles as $articleData) {
            $article = $this->find($articleData['id']);

            if ($article) {
                $article->qteStock = $articleData['qteStock'];
                $article->save();
                $updatedArticles[] = $article;
            } else {
                $notFoundArticles[] = $articleData['id'];
            }
        }

        return [
            'updated' => $updatedArticles,
            'not_found' => $notFoundArticles
        ];
    }

    // Filtrage des articles par stock
    public function filterByStock(Request $request)
    {
        $stock = $request->input('stock', 0);
        return Article::where('qteStock', '>=', $stock)->get();
    }
}
