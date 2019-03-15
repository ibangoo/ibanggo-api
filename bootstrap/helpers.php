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