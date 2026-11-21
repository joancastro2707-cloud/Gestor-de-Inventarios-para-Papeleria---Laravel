<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $qrBase64 = null;
        $secret = $user->google2fa_secret;

        if (! $user->google2fa_enabled) {
            if (! $secret) {
                $secret = Google2FA::generateSecretKey();
                $user->update(['google2fa_secret' => $secret]);
            }

            $qrUrl = Google2FA::getQRCodeUrl(
                config('app.name', 'PapeleriaPOS'),
                $user->email,
                $secret
            );

            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrBase64 = base64_encode($writer->writeString($qrUrl));
        }

        return view('auth.two-factor-setup', [
            'enabled' => $user->google2fa_enabled,
            'secret' => $secret,
            'qrBase64' => $qrBase64,
        ]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (! $user->google2fa_secret || ! Google2FA::verifyKey($user->google2fa_secret, $request->code)) {
            return back()->withErrors(['code' => 'El código de verificación es inválido.']);
        }

        $user->update(['google2fa_enabled' => true]);

        return redirect()->route('2fa.setup')->with('status', 'Autenticación de dos factores activada.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $request->user()->update([
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
        ]);

        return redirect()->route('2fa.setup')->with('status', 'Autenticación de dos factores desactivada.');
    }
}