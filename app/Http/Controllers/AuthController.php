<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // 1. EZ HIÁNYZOTT: A bejelentkező űrlap megjelenítése
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Bejelentkezés feldolgozása
    public function login(Request $request, ApiService $api)
    {
        // API hívás a bejelentkezéshez
        $response = $api->post('/users/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            // Token mentése a sessionbe (a backend válasza alapján: user.token)
            $token = $response->json('user.token'); 
            
            Session::put('api_token', $token);
            return redirect()->route('writers.index');
        }

        return back()->withErrors(['message' => 'Hibás adatok vagy sikertelen bejelentkezés.']);
    }

    // 3. Kijelentkezés
    public function logout()
    {
        Session::forget('api_token');
        return redirect()->route('login');
    }
}