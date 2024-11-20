<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            $user = Auth::user();
    
            if ($user->role === 'admin') {
                // Redirect to admin page
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'user') {
                // Redirect to user's task page
                return redirect()->route('topshiriq.myTasks');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'You do not have permission to access this application.',
                ]);
            }
        }
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
