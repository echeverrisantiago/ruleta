<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouletteOptions extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'roulette_options';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'probability',
        'background_color',
        'background_image',
        'win',
        'keep_trying',
        'roulette_description',
        'lost_result'
    ];

    public static $rules = [
        'descripcion' => 'required|string',
        'probability' => 'required|numeric',
        'win' => 'required|boolean',
        'keep_trying' => 'required|boolean',
        'roulette_description' => 'required|string',
        'lost_result' => 'required|boolean',
    ];
}
