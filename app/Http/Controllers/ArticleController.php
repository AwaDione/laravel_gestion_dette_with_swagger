<?php

namespace App\Http\Controllers;

use App\Enums\StatusResponseEnum;
use App\Enums\HttpResponseEnum; // Import de l'énumération pour les codes HTTP
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateStockArticleRequest;
use App\Services\ArticleServiceInterface;
use App\Traits\RestResponseTrait;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use RestResponseTrait;

    private $articleService;

    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    // Mise à jour de la quantité en stock par ID
    public function updateStockById(UpdateStockArticleRequest $request, $id)
    {
        try {
            $updatedArticle = $this->articleService->update($id, $request->input('qteStock'));

            if (!$updatedArticle) {
                return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Article introuvable', HttpResponseEnum::HTTP_NOT_FOUND);
            }

            return $this->sendResponse($updatedArticle, StatusResponseEnum::SUCCESS, 'Quantité en stock mise à jour avec succès', HttpResponseEnum::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la mise à jour du stock: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la mise à jour du stock', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mise à jour en lot des quantités en stock
    public function updateStock(Request $request)
    {
        try {
            $this->validate($request, [
                'articles' => 'required|array',
                'articles.*.id' => 'required|integer|exists:articles,id',
                'articles.*.qteStock' => 'required|integer|min:1'
            ]);

            $result = $this->articleService->bulkUpdateStock($request->input('articles'));

            return $this->sendResponse($result, StatusResponseEnum::SUCCESS, 'Mise à jour en lot effectuée avec succès', HttpResponseEnum::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la mise à jour en lot des stocks: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la mise à jour des stocks', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Liste des articles
    public function index()
    {
        try {
            $articles = $this->articleService->all();

            if ($articles->isEmpty()) {
                return $this->sendResponse([], StatusResponseEnum::SUCCESS, 'Pas d\'articles', HttpResponseEnum::HTTP_OK);
            }

            return $this->sendResponse($articles, StatusResponseEnum::SUCCESS, 'Liste des articles', HttpResponseEnum::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la récupération des articles: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la récupération des articles', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Récupération d'un article par ID
    public function getArticleById($id)
    {
        if (!is_numeric($id)) {
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'L\'identifiant doit être un nombre valide.', HttpResponseEnum::HTTP_BAD_REQUEST);
        }

        try {
            $article = $this->articleService->find($id);

            if (!$article) {
                return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Article non trouvé.', HttpResponseEnum::HTTP_NOT_FOUND);
            }

            return $this->sendResponse($article, StatusResponseEnum::SUCCESS, 'Article trouvé avec succès.', HttpResponseEnum::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la récupération de l'article: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la récupération de l\'article', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Récupération d'un article par libellé
    public function getArticleByLibelle(Request $request)
    {
        $request->validate(['libelle' => 'required|string']);

        try {
            $article = $this->articleService->findByLibelle($request->input('libelle'));

            if (!$article) {
                return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Article non trouvé.', HttpResponseEnum::HTTP_NOT_FOUND);
            }

            return $this->sendResponse($article, StatusResponseEnum::SUCCESS, 'Article trouvé avec succès.', HttpResponseEnum::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la récupération de l'article par libellé: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la récupération de l\'article', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Création d'un nouvel article
    public function store(ArticleRequest $request)
    {
        try {
            $articleData = $request->only(['libelle', 'reference', 'prix', 'quantite']);
            $article = $this->articleService->create($articleData);

            return $this->sendResponse($article, StatusResponseEnum::SUCCESS, 'Article enregistré avec succès', HttpResponseEnum::HTTP_CREATED);
        } catch (Exception $e) {
            \Log::error("Erreur lors de la création de l'article: " . $e->getMessage());
            return $this->sendResponse(null, StatusResponseEnum::ECHEC, 'Erreur lors de la création de l\'article', HttpResponseEnum::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
