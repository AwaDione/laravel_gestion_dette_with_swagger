<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;

    public function definition()
    {
        return [
            'login' => $this->faker->unique()->userName,
            'password' => Hash::make('Password1!'), // mot de passe par défaut
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'role_id' => Role::inRandomOrder()->first()->id,
            'photo' => $this->faker->imageUrl(640, 480, 'people', true, 'User Photo'),
            'active' => $this->faker->boolean,
        
            'client_id' => Client::inRandomOrder()->first()->id,
            // 'client_id' => 2
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function admin(){
    //     return $this->state(fn (array $attributes) => [
    //         'role' => 'ADMIN',
    //     ]);
    // }

    // public function client()
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'role' => 'CLIENT',
    //     ]);
    // }
}
