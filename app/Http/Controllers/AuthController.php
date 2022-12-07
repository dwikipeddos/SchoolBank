<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $request->ensureNotRateLimited();
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if ($user) {
            $token = $user->createToken($request->userAgent() . ', ip:' . $request->ip())->plainTextToken;
            return response([
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response(['message' => 'wrong email or password'], Response::HTTP_FORBIDDEN);
        }
    }
}
