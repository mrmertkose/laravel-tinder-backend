<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\User;
use App\Models\UserPhoto;
use App\Traits\ApiResponse;
use App\Enums\ResultTypeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\UserDetailResource;
use App\Http\Requests\Api\ImageUploadRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use apiResponse;

    public function getFindUser(User $user): JsonResponse
    {
        try {
            $getToken = request()->bearerToken();
            $token = PersonalAccessToken::findToken($getToken);
            $user = $user->getFindUser($token->tokenable->id);
            return $this->apiResponse(ResultTypeEnum::Success, !is_null($user['info']) ? new UserDetailResource($user) : [], null, !is_null($user['info']) ? Response::HTTP_OK : Response::HTTP_NO_CONTENT);
        } catch (Throwable $error) {
            return $this->apiResponseCathError($error->getMessage());
        }
    }

    public function postUserPhoto(ImageUploadRequest $request, User $user, $id): JsonResponse
    {
        try {
            $getToken = request()->bearerToken();
            $token = PersonalAccessToken::findToken($getToken);
            $userCheck = $user->findOrFail($token->tokenable->id);

            $path = public_path('uploads');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $files = $request->file('image');
            foreach ($files as $key => $image) {
                $name = uniqid() . '.' . $image->extension();
                $image->move($path, $name);
                UserPhoto::create([
                    'user_id' => $userCheck->id,
                    'image_name' => $name,
                    'sort' => $key,
                ]);
            }
            return $this->apiResponse(ResultTypeEnum::Success, null, "Upload Successfully");
        } catch (Throwable $error) {
            return $this->apiResponseCathError($error->getMessage());
        }
    }
}
