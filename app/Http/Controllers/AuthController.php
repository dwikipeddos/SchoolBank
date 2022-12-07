<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $request->ensureNotRateLimited();
        $user = User::where('email', $request->email)->first();
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

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'logged out']);
    }
}
