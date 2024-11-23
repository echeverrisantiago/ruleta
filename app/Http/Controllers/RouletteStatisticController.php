<?php

namespace App\Http\Controllers;

use App\Models\RouletteStatistic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RouletteStatisticController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth.basic']);
    }
    
    /**
     * Método para mostrar las estadísticas en pantalla.
     *
     * @param  Request $request contiene la información del formulario de búsqueda.
     * @return array contiene toda la información de las estadísticas.
     * @author Santiago Echeverri
     */
    public function getStatistics(Request $request) {
        try {
            if (is_string($request->fecha)) {
                $dateIni = Carbon::now()->subDay($request->fecha)->format('Y-m-d');
                $dateFin = Carbon::now()->format('Y-m-d');
            } else if (is_array($request->fecha)) {
                $dateIni = $request->fecha[0];
                $dateFin = $request->fecha[1];
            } else {
                $dateIni = Carbon::now()->format('Y-m-d');
                $dateFin = Carbon::now()->format('Y-m-d');
            }
    
            $date = [$dateIni, $dateFin]; 
            
            $asesores = User::select('id','name')
            ->get();
            
            $statistics = RouletteStatistic::select('*')
            ->with([
                'asesor',
                'option',
                'amount'                
                ])
                ->when($request->asesor, function($query) use ($request) {
                    $query->where('users_id', $request->asesor);
                })
                ->where(function ($query) use ($request, $date) {
                    if ($request->fecha > 0) {
                        $query->whereBetween('created_at', $date);
                    } else {
                        $query->whereDate('created_at', Carbon::today());
                    }
                });
                
                if ($request->tipo == 1) {
                    $statistics = $statistics->orderBy('id', 'DESC');
                } else {
                    $statistics = $statistics->groupBy('code','id','users_id','roulette_options_id','money_amount_id','created_at','updated_at');
                }
                
                $statistics = $statistics->get();
                
            $collected = 0;
                
            if ($request->tipo == 2) {
                foreach ($statistics as $key => $statistic) {            
                    $collected = $collected + $statistic->amount->quantity;  
                }
            }
            
            $participantes = $this->getDataStatistics($request, $date);
            
            $ganadores = $this->getDataStatistics($request, $date, 'win');
            
            $perdedores = $this->getDataStatistics($request, $date, 'lose');
            
             
            return view('optionsStatistic', [
                'asesores'      => $asesores,
                'statistics'    => $statistics,
                'collected'     => $collected,
                'participantes' => $participantes,
                'ganadores'     => $ganadores,
                'perdedores'    => $perdedores,
                'errors' => ''
            ]);
        } catch (\Exception $e) {            
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return 'Ha habido un error al mostrar las estadísticas, contacte con el administrador.' .$e->getMessage();
        }
    }
    
    /**
     * Obtener los datos de las estadísticas.
     *
     * @param Request $request contiene los datos para hacer la consulta.
     * @param array|int $date contiene la fecha a filtrar.
     * @param string $type contiene el tipo de estadística.
     * @return Collection contiene las estadísticas.
     * @author Santiago Echeverri
     */
    public function getDataStatistics(Request $request, $date, string $type = '') {
        return RouletteStatistic::select('id')
            ->where(function ($query) use ($request, $date) {
                if ($request->fecha > 0) {
                    $query->whereBetween('created_at', $date);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
            })
            ->when($request->asesor, function($query) use ($request) {
                $query->where('users_id', $request->asesor);
            })
            ->whereHas('option', function($query) use ($type) {
                if ($type == 'win') {
                    $query->where('win', 1);
                } else if ($type == 'lose') {
                    $query->where('win', 0);
                }
            })
            ->with(['asesor'])
            ->get()
            ->count();
    }
    
    /**
     * deleteStatistics
     *
     * @param Request $request
     * @return void
     * @author Santiago Echeverri
     */
    public function deleteStatistics(Request $request) {
        Log::info($request->all());

        if (is_string($request->fecha)) {
            $dateIni = Carbon::now()->subDay($request->fecha)->format('Y-m-d');
            $dateFin = Carbon::now()->format('Y-m-d');
        } else if (is_array($request->fecha)) {
            $dateIni = $request->fecha[0];
            $dateFin = $request->fecha[1];
        }

        $date = [$dateIni, $dateFin];

        RouletteStatistic::select('*')
            ->when($request->asesor, function($query) use ($request) {
                $query->where('users_id', $request->asesor);
            })
            ->where(function ($query) use ($request, $date) {
                if ($request->fecha > 0) {
                    $query->whereBetween('created_at', $date);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
            })
            ->with([
                'amount'
            ])
            ->groupBy('code')
            ->delete();

        return redirect('/opciones/estadisticas')->with('success', 'Las estadísticas han sido eliminadas');
    }
}
