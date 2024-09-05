<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepositoryImpl implements ClientRepositoryInterface{

    public function all()
    {
        return Client::all();
    }

    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update(array $data, $id)
    {
        return Client::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return Client::destroy($id);
    }

    public function getByPhone($phone)
    {
        return Client::where('telephone', $phone)->first();
    }

    public function getById($id)
    {
        return Client::find($id);
    }

    public function clientWithUser($id)
    {
        return Client::with('user')->find($id);
    }
}
