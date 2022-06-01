<?php

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\ResultTypeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\Auth\AuthLoginRequest;
use App\Http\Requests\Api\Auth\AuthRegisterRequest;

class AuthController extends Controller
{
    use apiResponse;

    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            if (!auth()->attempt(request(['email', 'password']))) {
                return $this->apiResponse(ResultTypeEnum::Error, 'Unauthorized (Credentials Incorrect)');
            }

            $user = User::where('email', $request['email'])->first();
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->apiResponse(
                ResultTypeEnum::Success,
                [
                    'user' => new UserResource($user),
                    'access_token' => $token
                ],
                'Login Successful'
            );

        } catch (Throwable $error) {
            return $this->apiResponseCathError($error->getMessage());
        }
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        try {
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password'])
            ]);
            return $this->apiResponse(ResultTypeEnum::Success, null, 'User Created', Response::HTTP_CREATED);
        } catch (Throwable $error) {
            return $this->apiResponseCathError($error->getMessage());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->apiResponse(ResultTypeEnum::Success, null, 'Logged out');
        } catch (Throwable $error) {
            return $this->apiResponseCathError($error->getMessage());
        }
    }


}
