<?php
namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateMultipleStocksRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
  // Liste les articles avec filtrage de disponibilité
  public function index(Request $request)
  {
      $disponible = $request->query('disponible');
      $perPage = $request->query('per_page', 10); // Nombre d'articles par page, par défaut 10

      if ($disponible === 'oui') {
          // Articles disponibles (qteStock > 0)
          $articles = Article::where('qteStock', '>', 0)->paginate($perPage);
          $message = "Liste des articles disponibles";
      } elseif ($disponible === 'non') {
          // Articles non disponibles (qteStock = 0)
          $articles = Article::where('qteStock', '=', 0)->paginate($perPage);
          $message = "Liste des articles non disponibles";
      } else {
          // Tous les articles
          $articles = Article::paginate($perPage);
          $message = "Liste de tous les articles";
      }

      if ($articles->isEmpty()) {
          return response()->json([
              'status' => Response::HTTP_OK,
              'data' => null,
              'message' => 'Pas d\'articles'
          ], Response::HTTP_OK);
      }

      return response()->json([
          'status' => Response::HTTP_OK,
          'data' => $articles, // La pagination inclut automatiquement les infos de pagination
          'message' => $message
      ], Response::HTTP_OK);
  }

    

    // Recherche d'un article par son ID
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => 411, // Code d'erreur personnalisé
                'data' => null,
                'message' => 'Objet non trouvé'
            ], 411);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $article,
            'message' => 'Article trouvé'
        ], Response::HTTP_OK);
    }

    // Recherche d'un article par son libellé via une requête POST
    public function findByLibelle(Request $request)
    {
        $libelle = $request->input('libelle');

        // Recherche de l'article par le libellé
        $article = Article::where('libelle', $libelle)->first();

        if (!$article) {
            return response()->json([
                'status' => 411, // Code d'erreur personnalisé
                'data' => null,
                'message' => 'Objet non trouvé'
            ], 411);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $article,
            'message' => 'Article trouvé'
        ], Response::HTTP_OK);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $article->update($request->validated());

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $article,
            'message' => 'Article mis à jour avec succès',
        ], Response::HTTP_OK);
        
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => null,
            'message' => 'Article supprimé avec succès',
        ], Response::HTTP_OK);
    }
    public function updateStock(UpdateStockRequest $request, $id)
    {
        // $id est récupéré depuis l'URL, via le paramètre {id} dans la route
        // Il est utilisé pour trouver l'article correspondant dans la base de données
        $article = Article::find($id);
// dd($article);
        // Si l'article n'existe pas, retourner une réponse 404
        if (!$article) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'data' => null,
                'message' => "Article non trouvé",
            ], Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour la quantité en stock avec la valeur passée dans la requête
        $article->qteStock += $request->qteStock;
        $article->save();

        // Retourner une réponse avec l'article mis à jour
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $article,
            'message' => "Quantité en stock mise à jour avec succès",
        ], Response::HTTP_OK);
    }


    public function updateMultipleStocks(UpdateMultipleStocksRequest $request)
    {
        $success = [];
        $error = [];

        foreach ($request->articles as $articleData) {
            $article = Article::find($articleData['id']);

            if ($article) {
                // Mettre à jour la quantité en stock
                $article->qteStock += $articleData['qteStock'];
                $article->save();

                $success[] = $article;
            } else {
                // dd($articleData['id']);

                $error[] = $articleData['id'];
            }
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'success' => $success,
                'error' => $error,
            ],
            'message' => "Mise à jour des stocks terminée",
        ], Response::HTTP_OK);
    }

      // Enregistrer un nouvel article
      public function store(ArticleRequest $request)
      {
            // Créer et enregistrer un nouvel article
          $article = Article::create([
              'libelle' => $request->input('libelle'),
              'prix' => $request->input('prix'),
              'qteStock' => $request->input('qteStock'),
          ]);
  
          // Réponse de succès
          return response()->json([
              'status' => 201, // Code 201 pour création
              'data' => $article,
              'message' => 'Article enregistré avec succès'
          ], 201);
      }
}
