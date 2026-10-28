<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 1. If the user has never set up 2FA, force them to the QR code setup page
        if (empty($user->two_factor_secret)) {
            return redirect()->route('2fa.setup');
        }

        // 2. If they have set it up but haven't verified their current session, ask for the PIN
        if (!session()->has('2fa_verified')) {
            return redirect()->route('2fa.verify');
        }

        // 3. If they have a secret and verified the PIN, grant access to the dashboard
        return $next($request);
    }
}   