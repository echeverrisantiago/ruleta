<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Roles extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'roles';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    public static $rules = [
        'name'      => 'required|string',
        'code'      => 'required|string',
    ];

    public static $rulesUpdate = [
        'name'      => 'nullable|string',
        'code'      => 'nullable|string',
    ];
}
