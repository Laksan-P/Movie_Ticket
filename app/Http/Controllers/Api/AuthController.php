<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use RespondsWithJson;

    public function login(Request $request)
    {
        // Validate incoming request data before processing
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string|max:255',
        ]);

        // Prevent SQL Injection using Laravel Eloquent ORM (parameterized queries)
        $user = User::where('email', $request->email)->first();

        // Secure password verification using Laravel Hash facade (bcrypt)
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // API login must not bypass Fortify two-factor authentication for web sessions.
        if ($user->hasEnabledTwoFactorAuthentication()) {
            throw ValidationException::withMessages([
                'email' => ['Two-factor authentication is required. Sign in through the web login to verify your code.'],
            ]);
        }

        auth()->guard('web')->login($user, $request->boolean('remember'));

        $tokenName = $request->input('device_name', 'api-token');

        // Protect API routes using Laravel Sanctum — issue personal access token
        return $this->jsonSuccess('Login successful.', [
            'token' => $user->createToken($tokenName)->plainTextToken,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        // Validate incoming request data before processing
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'device_name' => 'nullable|string|max:255',
        ]);

        // Prevent XSS attacks by sanitizing user input before storage
        $cleanName = strip_tags($request->name);

        // Prevent SQL Injection using Laravel Eloquent ORM; secure password hashing using Laravel Hash facade
        $user = User::create([
            'name' => $cleanName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        auth()->guard('web')->login($user);

        $tokenName = $request->input('device_name', 'api-token');

        // Protect API routes using Laravel Sanctum — issue personal access token
        return $this->jsonSuccess('User registered successfully.', [
            'token' => $user->createToken($tokenName)->plainTextToken,
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        // Revoke current Sanctum token on logout (API security)
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        auth()->guard('web')->logout();
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return $this->jsonSuccess('Logged out successfully.');
    }
}
