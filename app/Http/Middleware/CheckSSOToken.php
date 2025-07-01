<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckSSOToken
{
    public function handle(Request $request, Closure $next): Response
    {

        $ssoTokenCookie = $request->cookie('sso_token');
        $ssoEmail  = $request->cookie('sso_email');

        if (Auth::check() && !$ssoTokenCookie) {
            Auth::logout();
            // dd($ssoTokenCookie);

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // not for ecommerce
        // if (!Auth::check()) {
        //     if ($ssoTokenCookie && $ssoEmail) {
        //         $parts = explode('|', $ssoTokenCookie);
        //         $plainToken = $parts[1] ?? null;
        //         if ($plainToken) {
        //             $hashedToken = hash('sha256', $plainToken);
        //         }

        //         $user = User::where('email', $ssoEmail)->first();

        //         if ($user) {
        //             $exists = PersonalAccessToken::where('tokenable_id', $user->id)
        //                 ->where('token', $hashedToken)
        //                 ->exists();

        //             if ($exists) {
        //                 Auth::login($user);
        //             }
        //         }
        //     }
        // }

        return $next($request);
    }



    // public function handle(Request $request, Closure $next): Response
    // {

    //     $ssoTokenCookie = $request->cookie('sso_token');
    //     $ssoEmail  = $request->cookie('sso_email');

    //     $hashedToken = self::generateHashedToken($ssoTokenCookie);

    //     if (Auth::check()) {

    //         $exists = self::isHashTokenExists($hashedToken, auth()->user()->id);

    //         if (!$ssoTokenCookie || !$exists) {
    //             Auth::logout();
    //         }

    //         $request->session()->invalidate();
    //         $request->session()->regenerateToken();
    //     }

    //     // else if (!Auth::check()) {
    //     //     if ($ssoTokenCookie && $ssoEmail) {

    //     //         $user = User::where('email', $ssoEmail)->first();

    //     //         if ($user) {

    //     //             $exists =self::isHashTokenExists($hashedToken, $user->id);

    //     //             if ($exists) {
    //     //                 Auth::login($user);
    //     //             }
    //     //         }
    //     //     }
    //     // }

    //     return $next($request);
    // }


    private function generateHashedToken($ssoTokenCookie)
    {
        $hashedToken = '';
        $parts = explode('|', $ssoTokenCookie);
        $plainToken = $parts[1] ?? null;
        if ($plainToken) {
            $hashedToken = hash('sha256', $plainToken);
        }

        return $hashedToken;
    }

    private function isHashTokenExists($hashedToken, $userId)
    {
        return PersonalAccessToken::where('tokenable_id', $userId)
                ->where('token', $hashedToken)
                ->exists();

    }


}
