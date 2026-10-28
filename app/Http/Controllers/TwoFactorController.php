<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    public function setup()
    {
        $admin = Auth::user(); 
        $google2fa = new Google2FA();
        
        // Generate and save a new 32-character secret
        $secret = $google2fa->generateSecretKey(32);
        $admin->two_factor_secret = $secret;
        $admin->save();

        // Build the QR Code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Aegis-Guard Command Center',
            $admin->username,
            $secret
        );

        // Convert to an SVG image
        $renderer = new ImageRenderer(new RendererStyle(250), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('auth.2fa_setup', compact('qrCodeSvg', 'secret'));
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);
        
        $admin = Auth::user();
        $google2fa = new Google2FA();
        
        $isValid = $google2fa->verifyKey($admin->two_factor_secret, $request->otp);
        
        if ($isValid) {
            // Flag the session as fully verified
            $request->session()->put('2fa_verified', true);
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors(['otp' => 'Invalid authentication code. Please try again.']);
    }
}