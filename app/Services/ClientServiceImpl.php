<?php

namespace App\Services;
use App\Facades\ClientRepositoryFacade as clientRepository;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Facades\UploadServiceFacade as UploadService;
use Illuminate\Http\UploadedFile;

class ClientServiceImpl implements ClientServiceInterface
{

    public function all()
    {
        return clientRepository::all();
    }

    public function create(array $data)
    {
        try{
            DB::beginTransaction();
            $client = clientRepository::create($data);
            if(isset($data['user'])){
                $user = $data['user'];
                $roleId = $user['role']['id'] ?? null;
                if (!$roleId) {
                    throw new Exception('Le rôle de l\'utilisateur est manquant.');
                }
    
                $role = Role::find($roleId);
                if (!$role) {
                    throw new Exception('Rôle non trouvé avec l\'ID: ' . $roleId);
                }

                $photo = $user['photo'] ?? 'https://cdn-icons-png.flaticon.com/128/17346/17346780.png';
                if($photo instanceof UploadedFile){
                    $photo = UploadService::uploadImage($photo);
                }

                $user = User::create([
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'login' => $user['login'],
                    'password' => $user['password'],
                    'photo' => $photo,
                    'role_id' => $role->id
                ]);
                $client->user()->associate($user);
                $client->save();
            }
            DB::commit();
            return $client;
        }catch(Exception $e){
            DB::rollBack();
            Log::error('Erreur lors de la création du client ou de l\'utilisateur: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('Erreur lors de la création du client ou de l\'utilisateur: ' . $e->getMessage());
        }
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
        return clientRepository::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        return clientRepository::destroy($id);
    }

    public function getByPhone($phone)
    {
        // TODO: Implement getByPhone() method.
        return clientRepository::where('telephone', $phone)->first();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
        return clientRepository::find($id);
    }

    public function clientWithUser($id)
    {
        // TODO: Implement clientWithUser() method.
        return clientRepository::with('user')->find($id);
    }
}