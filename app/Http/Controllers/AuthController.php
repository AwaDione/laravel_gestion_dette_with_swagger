<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Passport;
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('login', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('TokenName')->accessToken;

            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => ['token' => $token],
                'message' => 'Login réussi',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'data' => null,
                'message' => 'Non autorisé',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
