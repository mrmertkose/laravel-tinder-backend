<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\User;
use App\Models\UserPhoto;
use App\Models\UserEvent;
use App\Traits\ApiResponse;
use App\Traits\ImageUpload;
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
    use ApiResponse;
    use ImageUpload;

    private $userCheck;
    private User $user;

    public function __construct(User $user)
    {
        $getToken = request()->bearerToken();
        $token = PersonalAccessToken::findToken($getToken);
        $this->userCheck = $user->findOrFail($token->tokenable->id);
        $this->user = $user;
    }

    public function findUser(): JsonResponse
    {
        try {
            $findUser = $this->user->findUser($this->userCheck->id);
            return $this->success(!is_null($findUser['info']) ? new UserDetailResource($findUser) : null);
        } catch (Throwable $error) {
            return $this->failure($error->getMessage());
        }
    }

    public function activityUser(Request $request): JsonResponse
    {
        try {
            $data = null;
            $activity = ActivityTypeEnum::activity[$request->input('direction')];
            if ($activity != 0) {
                $toUserId = Crypt::decryptString($request->input('userId'));
                $newEvent = UserEvent::create([
                    'user_id' => $this->userCheck->id,
                    'user_liked_id' => $toUserId,
                    'status' => $activity,
                ]);
                $data = $this->user->matchUser($newEvent);
                $data = count($data) != 0 ? new UserDetailResource($data) : null;
            }
            return $this->success($data, null);
        } catch (Throwable $error) {
            return $this->failure($error->getMessage());
        }
    }

    public function photoUploadUser(ImageUploadRequest $request): JsonResponse
    {
        try {
            $files = $request->file('image');
            foreach ($files as $key => $image) {
                $name = $this->upload($image, 'uploads/');
                UserPhoto::create([
                    'user_id' => $this->userCheck->id,
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
