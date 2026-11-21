<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorChallengeController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('2fa:user:id');
        $user = User::find($userId);

        if (! $user || ! $user->google2fa_secret) {
            return redirect()->route('login');
        }

        if (! Google2FA::verifyKey($user->google2fa_secret, $request->code)) {
            return back()->withErrors(['code' => 'Código TOTP incorrecto. Intenta nuevamente.']);
        }

        $remember = $request->session()->pull('2fa:remember', false);
        $request->session()->forget('2fa:user:id');

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}