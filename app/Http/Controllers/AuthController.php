<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(LoginRequest $request)
    {
        $usuario = Usuario::where('codigo', $request->codigo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput(['codigo' => $request->codigo])
                ->with('error', 'Verifica que el código o contraseña sean correctos.');
        }

        Auth::login($usuario, $request->boolean('remember'));

        return redirect()->route('inicio');
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}



