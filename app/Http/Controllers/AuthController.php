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
        return view('themes.welcome.welcome');
    }

public function auth_user(Request $request)
{
    $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = DB::table('users')
        ->join('role', 'users.role_id', '=', 'role.id')
        ->join('branches', 'users.branch_id', '=', 'branches.id') 
        ->where('users.email', $request->email)
        ->select('users.*', 'role.role as user_role', 'branches.branch_name as branch_name')
        ->first();
        // dd($user);

    if ($user && Hash::check($request->password, $user->password)) {

        session()->regenerate();

        session([
            'id'        => $user->id,
            'email'      => $user->email,
            'user_role' => $user->user_role,  
            'branch_id' => $user->branch_id,
            'branch_name' => $user->branch_name,   
        ]);

        // dd(session()->all());

        return redirect()->route('dashboard');
    }

    return redirect()->back()->with('errorMessage', 'Invalid credentials.');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
