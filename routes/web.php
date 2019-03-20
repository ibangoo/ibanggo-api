<?php

Route::get('/wechat-login', function () {
    return Socialite::with('weixinweb')->redirect();
});

Route::get('/wechat-callback', function () {
    return view('wechat');
});