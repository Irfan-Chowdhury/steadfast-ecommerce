<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SSOController extends Controller
{
    public function ssoLogout(Request $request)
    {
        $email = $request->input('email');
        $fullToken = $request->input('token');

        $parts = explode('|', $fullToken);
        $plainToken = $parts[1] ?? null;
        if ($plainToken) {
            $hashedToken = hash('sha256', $plainToken);
        }

        $user = User::where('email', $email)->first();
        if ($user && $hashedToken) {
            // // Delete matching token
            $user->tokens()->where('token', $hashedToken)->delete();
        }

        return response()->json(['message' => 'Token deleted successfully'], 200);
    }
}
