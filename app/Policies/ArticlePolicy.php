<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    // Autoriser tout d'abord les administrateurs
    public function before(User $user, $ability)
    {
        if ($user->role->name === 'admin') {
            return true;
        }
    }

    /**
     * Déterminer si l'utilisateur peut afficher l'article.
     */
    public function view(User $user, Article $article)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']);
    }

    /**
     * Déterminer si l'utilisateur peut créer des articles.
     */
    public function create(User $user)
    {
        return $user->role->name === 'boutiquier';
    }

    /**
     * Déterminer si l'utilisateur peut mettre à jour l'article.
     */
    public function update(User $user, Article $article)
    {
        return $user->role->name === 'boutiquier';
    }

    /**
     * Déterminer si l'utilisateur peut supprimer l'article.
     */
    public function delete(User $user, Article $article)
    {
        return $user->role->name === 'boutiquier';
    }
}
