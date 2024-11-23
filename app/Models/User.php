<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user',
        'state',
        'rol_id',
        'email',
        'password',
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

    public static $rules = [
        'name' => 'required|string',
        'user'  => 'required|string',
        'rol_id'   => 'required|numeric',
        'state' => 'nullable|boolean',
        'email' => 'nullable',
        'password' => 'required|string',
    ];

    public static $rulesUpdate = [
        'name' => 'nullable|string',
        'user'  => 'nullable|string',
        'rol_id'   => 'nullable|numeric',
        'state' => 'nullable|boolean',
        'email' => 'nullable',
        'password' => 'nullable|string',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Mutador para encriptar la contraseña al crear o actualizar usuarios.
     *
     * @param string $password contiene la contraseña sin encriptar.
     * @return string|void retorna la contraseña encriptada, si llega un string vacío retorna void.
     * @author Santiago Echeverri
     */
    public function setPasswordAttribute(string $password)
    {
        if (trim($password) == '') {
            return;
        }

        $this->attributes['password'] = bcrypt($password);
    }

    public function Rol() {
        return $this->belongsTo(Roles::class, 'rol_id', 'id');
    }
}
