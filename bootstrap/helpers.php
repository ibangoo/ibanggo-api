<?php

if (!function_exists('transform_error_message')) {
    /**
     * 根据开发环境输出错误信息
     *
     * @param $errorMessage
     * @param $message
     *
     * @return string
     */
    function transform_error_message($errorMessage, $message)
    {
        if (app()->environment() !== 'production') {
            return $errorMessage;
        }

        return $message;
    }
}

if (!function_exists('get_request_parameters')) {
    /**
     * 获取 HTTP Request 特定参数
     *
     * @param $request
     *
     * @return array
     */
    function get_request_parameters($request)
    {
        return $request->only(array_keys($request->rules()));
    }
}