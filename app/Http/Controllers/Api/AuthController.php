<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email ?? $request->username . '@ledger.local',
            'name' => $request->username,
            'password' => $request->password,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json(['user' => $user], 201)
            ->cookie('auth_token', $token, 60 * 24 * 365, '/', null, true, true, false, 'Strict');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('auth')->plainTextToken;
            return response()->json(['user' => $user])
                ->cookie('auth_token', $token, 60 * 24 * 365, '/', null, true, true, false, 'Strict');
        }

        throw ValidationException::withMessages([
            'username' => ['Credenciales inválidas.'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $request->user()?->currentAccessToken()?->delete();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        foreach (range(-6, 6) as $offset) {
            Cache::forget('dashboard_' . date('Y-m', strtotime("$offset months")) . '_user_' . $userId);
        }

        return response()->json(['message' => 'Sesión cerrada'])
            ->withoutCookie('auth_token');
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
