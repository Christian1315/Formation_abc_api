<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        "denomination",
        'phone',
        'email',
        'password',
        "ifu",
        "rccm"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    #MANY TO MANY RELATIONSHIP(UN USER PEUT AVOIR PLUISIEURS ROLES & DE ME UN ROLE PEUT APPARTENIR A PLUISIEURS USERS)
    public function roles(): BelongsToMany
    {
        return $this->BelongsToMany(Role::class, 'roles_users', 'user_id', 'role_id');
    }

    #ONE TO MANY RELATIONSHIP(UN USER[celui qui a le role **is_transporter**] PEUT AJOUTER PLUISIEURS MOYENS DE TRANSPORT)
    function transports(): HasMany
    {
        return $this->hasMany(Transport::class, "owner");
    }

    #ONE TO MANY RELATIONSHIP(UN USER[celui qui a le role **is_sender**] PEUT AJOUTER PLUISIEURS FRETS)
    function frets(): HasMany
    {
        return $this->hasMany(Frets::class,"owner");
    }


    #ONE TO MANY RELATIONSHIP(UN USER PEUT RECEVOIR PLUISIEURS NOTIFICATIONS)
    function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }
}
