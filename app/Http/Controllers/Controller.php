<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * 响应服务端错误 500
     *
     * @param $message
     *
     * @return mixed
     */
    public function responseErrorInternal($message)
    {
        return $this->response->errorInternal($message);
    }

    /**
     * 响应客户端参数错误 422
     *
     * @param $message
     */
    public function responseErrorInvalidArgument($message)
    {
        return $this->response->error($message, 422);
    }

    /**
     * 响应客户端参数错误 404
     *
     * @param $message
     */
    public function responseErrorNotFound($message)
    {
        return $this->response->errorNotFound($message);
    }

    /**
     * 响应客户端参数错误 422
     *
     * @param array  $data
     * @param int    $statusCode
     * @param string $message
     *
     * @return mixed
     */
    public function responseArray($data = [], $statusCode = 200, $message = '请求成功')
    {
        return $this->response->array([
            'data' => $data,
            'message' => $message,
            'status_code' => $statusCode,
        ])->setStatusCode($statusCode);
    }

    /**
     * 生成用户 Json Web Token
     *
     * @param $user
     *
     * @return array
     */
    public function generateJsonWebToken($user): array
    {
        return [
            'access_token' => auth()->guard()->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => auth()->guard()->factory()->getTTL() * 60,
        ];
    }
}
