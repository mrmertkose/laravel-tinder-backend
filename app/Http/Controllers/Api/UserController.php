<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\User;
use App\Models\UserPhoto;
use App\Models\UserEvent;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\ActivityTypeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\UserDetailResource;
use App\Http\Requests\Api\ImageUploadRequest;

class UserController extends Controller
{
    use apiResponse;

    public function findUser(): JsonResponse
    {
        try {
            $getToken = request()->bearerToken();
            $token = PersonalAccessToken::findToken($getToken);
            $user = User::findUser($token->tokenable->id);
            return $this->success(!is_null($user['info']) ? new UserDetailResource($user) : null);
        } catch (Throwable $error) {
            return $this->failure($error->getMessage());
        }
    }

    public function userActivity(Request $request, User $user): JsonResponse
    {
        $getToken = request()->bearerToken();
        $token = PersonalAccessToken::findToken($getToken);
        $userCheck = $user->findOrFail($token->tokenable->id);

        $activity = ActivityTypeEnum::activity[$request->input('direction')];
        $data = null;
        if ($activity != 0) {
            $toUserId = Crypt::decryptString($request->input('userId'));
            $newEvent = UserEvent::create([
                'user_id' => $userCheck->id,
                'user_liked_id' => $toUserId,
                'status' => $activity,
            ]);
            $data = User::MatchUser($newEvent);
            $data = count($data) != 0 ? new UserDetailResource(User::MatchUser($newEvent)) : null;
        }
        return $this->success($data, null);
    }

    public function postUserPhoto(ImageUploadRequest $request, User $user): JsonResponse
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
            return $this->success(null, "Upload Successfully");
        } catch (Throwable $error) {
            return $this->failure($error->getMessage());
        }
    }
}
