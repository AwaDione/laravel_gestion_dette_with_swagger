<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepositoryImpl implements ArticleRepositoryInterface
{
    public function all()
    {
        return Article::all();
    }

    public function create(array $articles)
    {
        return Article::create($articles);
    }

    public function find($id)
    {
        return Article::findOrFail($id);  // Lève une exception si l'article n'est pas trouvé
    }

    public function update($id, array $articles)
    {
        $article = Article::find($id);

        if (!$article) {
            return false; // Retourne false si l'article n'est pas trouvé
        }

        $article->update($articles);
        return $article;
    }

    public function delete($id)
    {
        return Article::destroy($id); // Supprime l'article par son ID
    }

    public function findByLibelle($libelle)
    {
        return Article::where('libelle', $libelle)->first(); // Trouve par libelle
    }

    public function findByEtat($value)
    {
        return Article::where('etat', $value)->get(); // Retourne tous les articles par état
    }
}
