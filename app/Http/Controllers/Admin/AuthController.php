<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard')->with('info', 'You are already logged in.');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard')->with('info', 'You are already logged in.');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . auth('admin')->user()->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}