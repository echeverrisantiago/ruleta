<?php

namespace App\Http\Controllers;

use App\Models\AmountOptions;
use App\Models\RouletteStatistic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AmountMoneyController extends Controller
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
     * opcioneCantidadDinero
     *
     * @return View
     * @author Santiago Echeverri
     */
    public function index() : View {
        $data = AmountOptions::select('id','quantity','attempts','state')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('opcionesCantidad', ['data' => $data]);
    }
    
    /**
     * store
     *
     * @param  Request $request
     * @return RedirectResponse
     * @author Santiago Echeverri
     */
    public function store(Request $request) : RedirectResponse {
        try {
            $rouletteOption = AmountOptions::select('id')
            ->where('quantity', $request->quantity)
            ->first();

            if (!$rouletteOption) {            
                $option_data = [
                    'quantity' => $request->quantity,
                    'attempts' => $request->attempts
                ];
                AmountOptions::create($option_data);
            }

            return back()->with('success','La cantidad ha sido registrada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al registrar la cantidad']);
        }
    }
    
    /**
     * update
     *
     * @param  Request $request
     * @return void
     * @author Santiago Echeverri
     */
    public function update(Request $request) : RedirectResponse {
        try {
            $request->validate(AmountOptions::$rulesUpdate);

            $rouletteOption = AmountOptions::select('id','state')
                ->where('id', $request->id)
                ->first();

            if ($rouletteOption) {
                $state = $rouletteOption->state;
                Log::info($request->all());
                $rouletteOption->update($request->all());
                if ($request->has('state')) {
                    if ($state != $rouletteOption->state) {    
                        $accion = $rouletteOption->state == 1 ? 'activada' : 'inactivada';
                        return back()->with('success', "La cantidad ha sido $accion");
                    }
                }
            } else {
                return back()->withErrors(['msg' => 'Ha habido un error al actualizar la cantidad']);
            }

            return back()->with('success','La cantidad ha sido actualizada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al actualizar la cantidad']);
        }
    }
    
    /**
     * destroy
     *
     * @param  int $id Contiene el id de la opción.
     * @return void
     * @author Santiago Echeverri
     */
    public function destroy(int $id) : RedirectResponse { 
        try {
            $amountOptions = AmountOptions::select('id')
                ->where('id',$id)
                ->first();
            
            if ($amountOptions) {
                $statistics = RouletteStatistic::select('id')
                    ->where('money_amount_id', $id);

                if (!empty($statistics->get())) {
                    $statistics->delete();
                }

                $amountOptions->delete();
            }

            return redirect('opciones/cantidad')->with('success','La cantidad ha sido eliminada');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al eliminar la cantidad']);
        }
    }
}
