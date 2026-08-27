<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.orders.index');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $correctPassword = config('services.admin.password');

        if (! $correctPassword || ! hash_equals($correctPassword, $request->input('password'))) {
            return back()
                ->withErrors(['password' => 'Incorrect password.'])
                ->onlyInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->intended(route('admin.orders.index'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('success', 'Logged out.');
    }
}
