<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // 🔐 Login view
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔐 Login action
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required', // email / username / phone
            'password' => 'required',
        ]);

        $login = $request->login;
        $password = $request->password;

        $user = User::where('email', $login)->orWhere('username', $login)->orWhere('phone', $login)->first();
        if (!$user) {
            return back()->withErrors([
                'login' => 'Invalid credentials',
            ]);
        }

        if (!Auth::attempt(['email' => $user->email, 'password' => $password])) {
            return back()->withErrors([
                'login' => 'Invalid credentials',
            ]);
        }
        $request->session()->regenerate();

        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.services');
    }


    // 📝 Register view
    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'username'      => 'required|string|max:255|unique:users,username',
            'phone'         => 'required|string|max:255|unique:users,phone',
            'password'      => 'required|min:6|confirmed',
            'referral_code' => 'nullable|exists:users,referral_code',
        ], [

            // Name
            'name.required' => 'নাম দেওয়া বাধ্যতামূলক',
            'name.max'      => 'নাম ২৫৫ অক্ষরের বেশি হতে পারবে না',

            // Email
            'email.required' => 'ইমেইল দেওয়া বাধ্যতামূলক',
            'email.email'    => 'সঠিক ইমেইল দিন',
            'email.unique'   => 'এই ইমেইল আগে থেকেই ব্যবহার করা হয়েছে',

            // Password
            'password.required'  => 'পাসওয়ার্ড দিতে হবে',
            'password.min'       => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',

            // Referral
            'referral_code.exists' => 'রেফার কোড সঠিক না',
        ]);

        // 🔍 Find referrer
        $referrer = null;
        if (!empty($data['referral_code'])) {
            $referrer = User::where('referral_code', $data['referral_code'])->first();
        }

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'username'          => $data['username'],
            'phone'             => $data['phone'],
            'password'          => Hash::make($data['password']),
            'show_password'     => $data['password'],
            'remember_token'    => Str::random(10),
            'status'            => 'active',
            'role'              => 'user',
            'referral_code'     => strtoupper(Str::random(8)),
            'referred_by'       => $referrer?->id,

        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
