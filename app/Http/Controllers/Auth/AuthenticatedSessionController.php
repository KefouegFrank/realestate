<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();


        $url = '';
        // Condition statement to check the role of the user's credentials entered and send them to their respective dashboard

        if ($request->user()->role === 'admin') {
            $url = 'admin/dashboard';
            // return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        } elseif ($request->user()->role === 'agent') {
            $url = 'agent/dashboard';
        } elseif ($request->user()->role === 'user') {
            $url = '/dashboard';
        }

        //the variable $url is then passed to redirect  
        return redirect()->intended($url);

        // return redirect()->intended(RouteServiceProvider::HOME);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
