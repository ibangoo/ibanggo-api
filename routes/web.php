<?php

Route::get('/wechat-login', function () {
    return Socialite::with('weixinweb')->redirect();
});