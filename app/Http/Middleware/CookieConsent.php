<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CookieConsent
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $cookieAccepted = $request->cookie('cookie_consent');

        $needsConsent = true;

        if ($user && $user->gdpr_consent) {
            $needsConsent = false;
        } elseif ($cookieAccepted) {
            $needsConsent = false;
        }

        view()->share('cookieConsentNeeded', $needsConsent);

        return $next($request);
    }
}
