<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Client;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{   
//     public function register(RegisterRequest $request)
//     {
//     //  dd("users");

//         DB::beginTransaction();

//         try {
//             $client = Client::findOrFail($request->client_id);
//             // dd($client);
//         // Récupérer l'ID du rôle "boutiquier"
//         $roleBoutiquier = Role::where('name', 'boutiquier')->firstOrFail();
//         $client = Client::find($request->client_id);
//             $user = User::create([
//                 'login' => $request->login,
//                 'password' => $request->password, // Le mot de passe est hashé automatiquement via le cast
//                 'nom' => $client->surname,
//                 'prenom' => $client->surname, 
//                 'role_id' => $roleBoutiquier->id, 
//                 'photo' => $request->photo,
//                 'client_id' => $client->id, 
//             ]);

//             DB::commit();

//             return response()->json([
//                 'status' => Response::HTTP_CREATED,
//                 'data' => [
//                     'user' => $user,
//                     'client' => $client,
//                 ],
//                 'message' => 'Client enregistré avec succès',
//             ], Response::HTTP_CREATED);
//         } catch (\Exception $e) {
//             DB::rollBack();

//             return response()->json([
//                 'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
//                 'data' => null,
//                 'message' => 'Échec de l\'enregistrement : ' . $e->getMessage(),
//             ], Response::HTTP_INTERNAL_SERVER_ERROR);
//         }
//     }

public function register(UserRequest $request)
{
    $validated = $request->validated();

    $role = Role::where('name', $validated['role'])->first();
    if (!$role) {
        return response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => 'Le rôle spécifié n\'existe pas.',
        ], Response::HTTP_BAD_REQUEST);
    }

    $user = User::create([
        'login' => $validated['login'],
        'password' =>$validated['password'],
        'nom' => $validated['nom'],
        'prenom' => $validated['prenom'],
        'role_id' => $role->id,
        'active' => true, // Valeur par défaut
    ]);

    return response()->json([
        'status' => Response::HTTP_CREATED,
        'data' => $user,
        'message' => 'Utilisateur créé avec succès.',
    ], Response::HTTP_CREATED);
}

public function index(UserRequest $request)
{
    $query = User::query();

    if ($request->has('role')) {
        $role = $request->input('role');
        $roleId = Role::where('name', $role)->value('id');
        if ($roleId) {
            $query->where('role_id', $roleId);
        } else {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Le rôle spécifié n\'existe pas.',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    if ($request->has('active')) {
        $active = $request->input('active') === 'oui' ? true : false;
        $query->where('active', $active);
    }

    $users = $query->paginate(10); // Pagination de 10 utilisateurs par page

    return response()->json([
        'status' => Response::HTTP_OK,
        'data' => $users,
        'message' => 'Liste des utilisateurs.',
    ], Response::HTTP_OK);
}
}


