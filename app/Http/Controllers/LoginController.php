<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{    
    /**
     * Constructor para validar si el usuario se encuentra logueado.
     *
     * @return Redirect
     */
    public function __construct() {
        $this->middleware(['auth.basic'])->only(['logout']);
        $this->middleware(['guest'])->only(['show','login']);
    }

    /**
     * Método para redirigir al usuario a la ruleta o al login en caso de que esté logueado o no.
     *
     * @return Redirect
     */
    public function redirect() {
        if (Auth::user()) {
            return redirect('ruleta');
        } else {
            return redirect('login');
        }
    }
    
    public function show() {
        return view('login');
    }

    /**
     * Método para iniciar sesión
     *
     * @param Request $request contiene los datos necesarios para iniciar sessión.
     * @return View|Redirect
     */
    public static function login(Request $request) {
        $request->validate([
            'user'     => 'required|string|min:3',
            'password' => 'required|string|min:3'
        ]);

        $user_data = [
            'user' => $request->user,
            'password' => $request->password,
            'state' => 1
        ];

        if (Auth::attempt($user_data, 1)) {
            return redirect('ruleta');
        } else {
            return back()->with('errors', collect(['Usuario o contraseña incorrectos']));
        }
    }
    
    /**
     * Método para cerrar sesión
     *
     * @param Request $request
     * @return Redirect
     * @author Santiago Echeverri
     */
    public function logout(Request $request) {
        if (Auth::user()) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/login');
        }
    }
}
