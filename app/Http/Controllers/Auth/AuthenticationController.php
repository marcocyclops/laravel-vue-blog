<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest2;

class AuthenticationController extends Controller
{
    
    public function login() {
        if (Auth::check()) {
            return redirect(route('wcms.posts'));
        }

        return inertia('wcms.Login');
    }

    public function authenticate(Request $request) {

        // throttling
        $throttleKey = $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'throttling' => 'Too many login attempts. Please try again in ' . RateLimiter::availableIn($throttleKey) . ' seconds.'
            ]);
        }

        // validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean']
        ]);
        
        // TODO: remember me seems not working
        // authenticate
        if (Auth::attempt(['email'=>$credentials['email'], 'password'=>$credentials['password']], $credentials['remember'])) {
            
            RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();

            return redirect()->intended(route('wcms.posts'));
        }

        // authenticate failed
        RateLimiter::hit($throttleKey);
        return back()->withErrors(['auth'=>'Incorrect email or password. ' . RateLimiter::remaining($throttleKey, 5)]);
    }

    public function logout(Request $request) {

        auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'));
    }
}
