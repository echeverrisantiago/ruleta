<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouletteStatistic extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'roulette_statistics';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'roulette_options_id',
        'money_amount_id',
        'code'
    ];

    public static $rules = [
        'users_id' => 'required|integer',
        'roulette_options_id' => 'required|integer',
        'money_amount_id' => 'required|integer',
        'code' => 'required|string'
    ];

    public static $rulesUpdate = [
        'users_id' => 'nullable|integer',
        'roulette_options_id' => 'nullable|integer',
        'money_amount_id' => 'nullable|integer',
        'code' => 'nullable|string'
    ];
    
    /**
     * winners
     *
     * @return BelongsTo
     * @author Santiago Echeverri
     */
    public function winners() : BelongsTo {
        return $this->belongsTo(RouletteOptions::class, 'roulette_options_id', 'id')
            ->where('win', 1);
    }
    
    /**
     * asesor
     *
     * @return BelongsTo
     * @author Santiago Echeverri
     */
    public function asesor() : BelongsTo {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    
    /**
     * option
     *
     * @return BelongsTo
     * @author Santiago Echeverri
     */
    public function option() : BelongsTo {
        return $this->belongsTo(RouletteOptions::class, 'roulette_options_id', 'id');
    }
    
    /**
     * amount
     *
     * @return BelongsTo
     * @author Santiago Echeverri
     */
    public function amount() : BelongsTo {
        return $this->belongsTo(AmountOptions::class, 'money_amount_id', 'id');
    }

     /**
     * intentosRealizados
     *
     * @return BelongsTo
     * @author Santiago Echeverri
     */
    public function intentosRealizados() : mixed {
        return $this->belongsToMany(RouletteStatistic::class, "roulette_statistics", 'id', 'id');
    }
}
