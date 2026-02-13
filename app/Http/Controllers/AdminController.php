<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {

            if (!auth()->check()) {
                return redirect()->route('login');
            }

            // ❌ User trying to access admin
            if (auth()->user()->role !== 'admin') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('home')
                    ->with('error', 'Permission denied');
            }

    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}


