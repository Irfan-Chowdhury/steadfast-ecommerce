<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Cookie;

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
    // public function store(LoginRequest $request): RedirectResponse
    // public function store(LoginRequest $request)
    // {
        // $response = Http::withHeaders(['Accept' => 'application/json'])->post('http://127.0.0.1:8001/api/cross-login', [
        //     'email' => '$user->email',
        // ]);
        // $response = Http::post('http://127.0.0.1:8001/api/cross-login', [
        //     'email' => 'irfan@gmail.com',
        // ]);
        // return $response->json();

        // $user = Auth::user();

        // // Call to foodpanda-app


        // // Token create
        // $token = $user->createToken('SSO-Token')->plainTextToken;



    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Token create to foodpanda-app
        $token = $user->createToken('SSO-Token')->plainTextToken;
        $redirectToUrl = config('app.sso_redirect_url');
        Http::withToken($token)->post("$redirectToUrl/api/cross-login", [
            'email' => $user->email
        ]);

        return redirect('/dashboard')
            ->withCookie(cookie('sso_token', $token, 60))
            ->withCookie(cookie('sso_email', $user->email, 60));
    }

    public function destroy(Request $request): RedirectResponse
    {
        self::tokenDestroyFromOtherSite($request);

        //Clear the SSO cookies
        Cookie::queue(Cookie::forget('sso_token'));
        Cookie::queue(Cookie::forget('sso_email'));

        // Clear session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/');
    }

    private function tokenDestroyFromOtherSite($request): void
    {
        $user = Auth::user();

        $parts = explode('|', $request->cookie('sso_token'));
        $plainToken = $parts[1] ?? null;
        if ($plainToken) {
            $hashedToken = hash('sha256', $plainToken);
        }

        $user->tokens()->where('token', $hashedToken)->delete();


        Http::post('http://127.0.0.1:8001/api/sso-logout', [
            'email' => $request->user()->email,
            'token' => $request->cookie('sso_token'),
        ]);
    }
}
