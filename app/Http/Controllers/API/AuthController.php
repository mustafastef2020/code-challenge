<?php

namespace App\Http\Controllers\API;

use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
      if (! $token = auth('api')->attempt($request->validated())) {
         abort(401, 'Invalid credentials');
      }
      
      return ApiResponse::format(true, 'User Logged in successfully', Response::HTTP_OK, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
      ]);
    }
}
