<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $request->ensureNotRateLimited();
        $user = User::where('email', $request->email)->with('roles')->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($request->userAgent() . ', ip:' . $request->ip())->plainTextToken;
                return response([
                    'user' => $user,
                    'token' => $token,
                ]);
            } else {
                return response(['message' => 'wrong password'], Response::HTTP_FORBIDDEN);
            }
        } else {
            return response(['message' => 'user with specified email doesn\'t exists'], Response::HTTP_FORBIDDEN);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response(['message' => 'logged out']);
    }
}
