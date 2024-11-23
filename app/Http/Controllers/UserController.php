<?php

namespace App\Http\Controllers;

use App\Models\RouletteStatistic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $data = User::select('id','name','user','state')
            ->whereHas('Rol', function ($query) {
                $query->where('name', 'asesor');
            })
            ->orderBy('id', 'ASC')
            ->paginate(10);

        return view('users', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->request->add(['rol_id' => 2]);
            Log::info($request->all());
            $request->validate(User::$rules);

            User::create($request->all());

            return back()->with('success', 'El asesor ha sido registrado');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al registrar el asesor']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info($request->all());
            $request->validate(User::$rulesUpdate);

            $user = User::select('id','state')
                ->where('id', $request->id)
                ->first();

            if ($user) {
                $state = $user->state;
                $user->update($request->all());
    
                if ($request->has('state')) {
                    if ($state != $user->state) {    
                        $accion = $user->state == 1 ? 'activado' : 'inactivado';
                        return back()->with('success', "El asesor ha sido $accion");
                    }
                }
                return back()->with('success', 'El asesor ha sido editado');                
            } else {
                return back()->withErrors(['msg' => 'Ha habido un error al editar el asesor']); 
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al editar el asesor']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::select('id')
                ->where('id', $id)
                ->first();

            if ($user) {
                $statistics = RouletteStatistic::select('id')
                    ->where('users_id', $id);

                if (!empty($statistics->get())) {
                    $statistics->delete();
                }

                $user->delete();
            }

            return redirect('opciones/usuarios')->with('success', 'El asesor ha sido eliminado');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getTraceAsString());
            return back()->withErrors(['msg' => 'Ha habido un error al eliminar el asesor']);
        }
    }
}
