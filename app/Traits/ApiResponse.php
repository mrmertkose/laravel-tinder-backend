<?php

namespace App\Traits;

use App\Enums\ResultTypeEnum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
//    protected function apiResponse($resultType, $data, $message = null, $code = Response::HTTP_OK): JsonResponse
//    {
//        $response = [];
//
//        $response["success"] = $resultType == ResultTypeEnum::Success ? true : false;
//
//        if (isset($data)) {
//
//            if ($resultType != ResultTypeEnum::Error) {
//                    $response['data'] = $data;
//            }
//
//            if ($resultType == ResultTypeEnum::Error) {
//                $response["errors"] = $data;
//            }
//
//        }
//
//        if (isset($message)) {
//            $response["message"] = $message;
//        }
//
//        return response()->json($response, $code);
//    }
//
//    protected function apiResponseCathError($data, $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
//    {
//        return $this->apiResponse(ResultTypeEnum::Error, $data, null, $code);
//    }


    protected function success($data = null, $message = null, $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failure($message, $status = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

}
