<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function index()
    {
        return Inertia::render("Login/Login");
    }


    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        Log::info("User" . $request->username . " was logged in successfully.");

        return Inertia::location(route("home")); // this will do the full RELOAD of the page so the user is correctly handled by the Inertia
        //return redirect()->intended(route("home")); // this will act as a SPA, but the user would not be loaded
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
