<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function main()
    {
        return view('welcome');
    }

    public function auth_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('errorMessage', 'Invalid email or password.');
        }

        if ($user->role_id != 1) {
            return back()->with('errorMessage', 'Unauthorized access.');
        }

        $request->session()->regenerate();

        Session::put([
            'id' => $user->id,
            'name' => $user->usr_name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'user_role' => 'Admin',
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
