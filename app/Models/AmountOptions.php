<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AmountOptions extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'money_amount';
    public $timestamps = true;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
        'attempts',
        'state'
    ];

    public static $rules = [
        'quantity'      => 'required|numeric',
        'attempts'      => 'required|numeric',
        'state'         => 'nullable|boolean'
    ];

    public static $rulesUpdate = [
        'quantity'      => 'nullable|numeric',
        'attempts'      => 'nullable|numeric',
        'state'         => 'nullable|boolean' 
    ];
}
