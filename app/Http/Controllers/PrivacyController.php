<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyController extends Controller
{
    public function show()
    {
        return view('privacy');
    }

    public function acceptCookies(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->gdpr_consent = true;
            $user->gdpr_consent_at = Carbon::now();
            $user->privacy_policy_version = 'v1.0';
            $user->save();

            return response()->json(['status' => 'ok', 'message' => 'Consent recorded for user.']);
        }

        $oneYearMinutes = 525600;
        $cookie = cookie('cookie_consent', '1', $oneYearMinutes, null, null, config('session.secure_cookie', false), true, false, 'Lax');

        return response()->json(['status' => 'ok', 'message' => 'Consent recorded in cookie.'])->cookie($cookie);
    }
}
