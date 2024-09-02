<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //      // Insérer les rôles par défaut
    //      DB::table('roles')->insert([
    //         ['name' => 'admin'],
    //         ['name' => 'boutiquier'],
    //         ['name' => 'client'],
    //     ]);
    // }
    public function run()
    {
        $roles = ['admin', 'boutiquier', 'client'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
