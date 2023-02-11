<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'legal_text' => ['required'],
            'kvkk_text' => ['required'],
            'specialty' => ['required', 'min:1'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'legal_text' => $data['legal_text'],
            'kvkk_text' => $data['kvkk_text'],
            'specialty' => $data['specialty'],
        ]);

        if ($user) {
            event(new Registered($user));
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json(["token" => $token , "type" => "Bearer"]);
        }

        return response()->json(["status" => false , "message" => "Kayıt başarısız oldu."]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$this->guard()->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 422);
        }

        $this->guard()->attempt($credentials);
        $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        return response()->json([
            'status_code' => '200',
            'message' => 'logged out successfully'
        ]);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }
}
