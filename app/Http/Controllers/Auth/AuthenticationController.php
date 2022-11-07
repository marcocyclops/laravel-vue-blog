<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

use App\Http\Controllers\Controller;

class AuthenticationController extends Controller
{
    /**
     * Show login form
     *
     * @return void
     */
    public function login() {
        if (Auth::check()) {
            return redirect(route('wcms.posts.index'));
        }

        return inertia('wcms.Login');
    }

    /**
     * Authenticate use login
     *
     * @param Request $request
     * @return void
     */
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

            return redirect()->intended();
        }

        // authenticate failed
        RateLimiter::hit($throttleKey);
        return back()->withErrors(['auth'=>'Incorrect email or password. ' . RateLimiter::remaining($throttleKey, 5)]);
    }

    /**
     * Logout wcms
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request) {

        auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'));
    }
}
