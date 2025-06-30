<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
        return view('auth.login');
    }


    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();


        try {
            $token = $user->createToken('SSO-Token')->plainTextToken;
            $redirectToUrl = config('app.sso_redirect_url');
            $response = Http::withToken($token)->post("$redirectToUrl/api/cross-login", [
                'email' => $user->email
            ]);
        } catch (Exception $e) {
            Log::info(["Error: " => $e->getMessage()]);
        }


        return redirect('/dashboard')
            ->withCookie(cookie('sso_token', $token, 60))
            ->withCookie(cookie('sso_email', $user->email, 60));

        // return redirect('/dashboard')
        //     ->withCookie('sso_token', $token, 60, '/', config('session.domain'), config('session.secure'), true, false, config('session.same_site'))
        //     ->withCookie('sso_email',  $user->email, 60, '/', config('session.domain'), config('session.secure'), true, false, config('session.same_site'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            self::tokenDestroyFromOtherSite($request);
        } catch (Exception $e) {
            Log::info(["Error: " => $e->getMessage()]);
        }

        //Clear the SSO cookies
        Cookie::queue(Cookie::forget('sso_token'));
        Cookie::queue(Cookie::forget('sso_email'));

        // Clear session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/login');
    }

    private function tokenDestroyFromOtherSite($request): void
    {
        $user = Auth::user();

        $parts = explode('|', $request->cookie('sso_token'));
        $plainToken = $parts[1] ?? null;
        $hashedToken = null;
        if ($plainToken) {
            $hashedToken = hash('sha256', $plainToken);
        }

        $user->tokens()->where('token', $hashedToken)->delete();

        $redirectToUrl = config('app.sso_redirect_url');

        Http::post("$redirectToUrl/api/sso-logout", [
            'email' => $request->user()->email,
            'token' => $request->cookie('sso_token'),
        ]);
    }
}
