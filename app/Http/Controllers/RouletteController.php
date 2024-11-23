<?php

namespace App\Http\Controllers;

use App\Models\AmountOptions;
use App\Models\RouletteOptions;
use App\Models\RouletteStatistic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RouletteController extends Controller
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
     * show
     *
     * @return void
     */
    public function show()
    {
        $rouletteOptions = RouletteOptions::select('id','description','roulette_description','lost_result','background_color','background_image','keep_trying','win','state')
            ->where('state', 1)
            ->orderBy('id', 'ASC')
            ->get();

        $amount = AmountOptions::select('id','quantity','attempts')
            ->where('state', 1)
            ->orderBy('quantity','ASC')
            ->get();

        return view('ruleta', ['data' => [
            'rouletteOptions' => $rouletteOptions, 
            'amount' => $amount,
            'angle' => $this->getAngle()
        ]]);
    }
    
    /**
     * guardarResultado
     *
     * @param  Request $request
     * @return void
     * @author Santiago Echeverri
     */
    public function guardarResultado(Request $request)
    {
        try {
            $user = auth()->user();
            $request->request->add(['users_id' => $user->id]);

            $request->validate(RouletteStatistic::$rules);

            RouletteStatistic::create($request->all());

            $angle = $this->getAngle();

            return response()->json([
                'data' => [
                    'angle' => $angle
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
    
    /**
     * Método para determinar el angulo donde se detiene la ruleta.
     *
     * @return int $result
     * @author Santiago Echeverri
     */
    public function getAngle() : int
    {
        $rouletteOptions = RouletteOptions::select('id','description','background_color','background_image','keep_trying','probability')
            ->orderBy('id', 'ASC')
            ->get();
        $results = [];
        $decimalMayor = 0;
        
        foreach ($rouletteOptions as $option) {
            $decimal = explode(".", $option['probability']);
            if (is_array($decimal)) {
                if (count($decimal) > 1) {
                    if (strlen($decimal[1]) > $decimalMayor) {
                        $decimalMayor = strlen($decimal[1]);
                    }
                }
            }
        }
        
        if ($decimalMayor > 0) {
            foreach ($rouletteOptions as $key => $option) {
                $option['probability'] = $option['probability'] * pow(10, $decimalMayor);
            }
        }
        
        foreach ($rouletteOptions as $option) {
            for ($i = 0; $i < $option['probability']; $i++) {
                $results[] = $option['id'];
            }
        }
        $key2 = $results ? array_rand($results, 1) : 0;
        $result = 0;
        
        foreach ($rouletteOptions as $key => $option) {
            if ($option['id'] == $results[$key2]) {
                Log::info('id ' . $option['id']);
                $result = $key;
            }
        }
        return $result + 1;
    }

    /**
     * opcionesRuleta
     *
     * @return void
     */
    public function opcionesRuleta()
    {
        $data = RouletteOptions::select('id', 'description', 'roulette_description', 'lost_result', 'probability', 'win', 'background_image', 'keep_trying','state');

        $probability = $data->get()->sum('probability');

        $data = $data->orderBy('id', 'DESC')->paginate(10);

        return view('opcionesRuleta', ['data' => $data, 'probability' => $probability]);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            $image = $request->file('background_image');
           
            $option_data = [
                'probability'          => $request->probability,
                'description'          => $request->description,
                'roulette_description' => $request->roulette_description,
                'background_color'     => NULL,
                'win'                  => 0,
                'keep_trying'          => 0
            ];

            if ($image) {
                $file_name = time() . '.' . $image->getClientOriginalExtension();
                $destination = public_path('/storage/opcionesRuleta');
                $destionation_bd = str_replace(public_path(), '', $destination);
                $full_path = $destionation_bd . '/' . $file_name;
                $option_data['background_image'] = $full_path;
            }

            if ($request->lost_result) {
                $option_data['lost_result'] = $request->lost_result;
            }

            $request->validate(RouletteOptions::$rules);

            $probabilities = $this->getProbabilities();

            if ($probabilities == 100) {
                return back()->withErrors(['msg' => 'No es posible superar el 100% de los porcentajes']);
            }

            if ($image) {
                if (!File::exists($destination)) {
                    File::makeDirectory($destination);
                }
                $image->move($destination, $file_name);
            }

            RouletteOptions::create($option_data);

            return back()->with('success', 'La opción ha sido registrada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al registrar la opción']);
        }
    }

    /**
     * getProbabilities
     *
     * @return int
     * @author Santiago Echeverri
     */
    public function getProbabilities(): int
    {
        $data = RouletteOptions::select('id', 'probability')
            ->get()
            ->sum('probability');

        return $data;
    }

    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        try {
            $rouletteOption = RouletteOptions::select('id', 'background_image', 'probability', 'state')
                ->where('id', $request->id)
                ->first();

            if ($rouletteOption) {
                $image = $request->file('background_image');
                if (!empty($image)) {
                    $file_name = time() . '.' . $image->getClientOriginalExtension();
                    $destination = public_path('/storage/opcionesRuleta');
                    $destionation_bd = str_replace(public_path(), '', $destination);
                    $full_path = $destionation_bd . '/' . $file_name;
                    Log::info($destination);
                    if (File::exists(public_path() . $rouletteOption->background_image)) {
                        File::delete(public_path() . $rouletteOption->background_image);
                    }

                    Log::info($destination);
                    if (!File::exists($destination)) {
                        File::makeDirectory($destination);
                    }
                    $request->background_image = $full_path;
                    $image->move($destination, $file_name);
                }

                $state = $rouletteOption->state;
                
                if ($request->has('state')) {
                    $rouletteOption->update([
                        'state' => $request->state
                    ]);
                    if ($state != $rouletteOption->state) {    
                        $accion = $rouletteOption->state == 1 ? 'activada' : 'inactivada';
                        return back()->with('success', "La opción ha sido $accion");
                    }
                }

                $option_data = [
                    'probability'          => $request->probability,
                    'description'          => $request->description,
                    'roulette_description' => $request->roulette_description,
                    'background_color'     => $request->background_color
                ];

                if (!empty($request->win) && !is_null($request->win)) {
                    $option_data['win'] = (int) $request->win;
                } else {
                    $option_data['win'] = 0;
                }

                if (!empty($request->keep_trying) && !is_null($request->keep_trying)) {
                    $option_data['keep_trying'] = $request->keep_trying;
                } else {
                    $option_data['keep_trying'] = 0;
                }

                if (!empty($image)) {
                    $option_data['background_image'] = $full_path;
                }

                if ($request->lost_result) {
                    $option_data['lost_result'] = $request->lost_result;
                }

                $probabilities = $this->getProbabilities();

                if ($probabilities == 100 && $request->probability > $rouletteOption->probability) {
                    return back()->withErrors(['msg' => 'No es posible superar el 100% de los porcentajes']);
                }              
                $rouletteOption->update($option_data);  
            } else {
                return back()->withErrors(['msg' => 'Ha habido un error al actualizar la opción']);
            }

            return back()->with('success', 'La opción ha sido actualizada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al actualizar la opción']);
        }
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete(int $id)
    {
        try {
            $rouletteOption = RouletteOptions::select('id', 'background_image')
                ->where('id', $id)
                ->first();

            if ($rouletteOption) {
                if (File::exists(public_path() . $rouletteOption->background_image)) {
                    File::delete(public_path() . $rouletteOption->background_image);
                }
                $statistics = RouletteStatistic::select('id')
                    ->where('roulette_options_id', $id);

                if (!empty($statistics->get())) {
                    $statistics->delete();
                }

                $rouletteOption->delete();
            }

            return redirect('opciones/ruleta')->with('success', 'La opción ha sido eliminada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al eliminar la opción']);
        }
    }
}
