<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Responses\FailAuthResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Метод отвечает за регистрацию пользователя
     *
     * @param UserRequest $request
     * @return FailResponse|SuccessResponse
     */
    public function register(UserRequest $request): FailResponse|SuccessResponse
    {
        try {
            $data = $request->safe()->except('avatar_url');
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $token = $user->createToken('auth_token')->plainTextToken;

            return new SuccessResponse(data: [
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception) {
            return new FailResponse();
        }
    }

    /**
     * Метод отвечает за аутентификацию пользователя на сайте
     *
     * @param LoginRequest $request
     * @return FailAuthResponse|SuccessResponse
     */
    public function login(LoginRequest $request): FailAuthResponse|SuccessResponse
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return new FailAuthResponse(trans('auth.failed'), Response::HTTP_UNAUTHORIZED);
            }

            $user = $request->user();
            $token = $user->createToken('auth_token');

            return new SuccessResponse(data: [
                'token' => $token->plainTextToken,
                'user' => $user
            ]);
        } catch (\Exception) {
            return new FailAuthResponse();
        }
    }

    /**
     * Метод отвечает за выход пользователя из ресурса
     *
     * @return FailResponse|SuccessResponse
     */
    public function logout(): FailResponse|SuccessResponse
    {
        try {
            Auth::user()->tokens()->delete();
            return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception) {
            return new FailResponse();
        }
    }
}
