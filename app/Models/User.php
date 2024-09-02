<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'password',
        'nom',
        'prenom',
        'role_id',
        'photo',
        'client_id', 
        'active', // Ajout du champ active
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed', // Laravel 10+ supporte le cast 'hashed'
        'active' => 'boolean',  
    ];

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

 

    public function client():HasOne
    {
        return $this->hasOne(Client::class);
    }
}
