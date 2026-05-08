<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === Role::Client) {
            return redirect()->route('client.dashboard');
        }

        return view('client.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'L\'email est obligatoire.',
            'email.email'       => 'Adresse email invalide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->role !== Role::Client) {
                Auth::logout();
                return back()->withErrors(['email' => 'Ce compte n\'est pas un compte client.']);
            }

            $request->session()->regenerate();
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}
