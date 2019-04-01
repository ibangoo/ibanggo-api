<?php

Route::get('/wechat-login', function () {
    return Socialite::with('weixinweb')->redirect();
})->name('wechat.login');

Route::get('/wechat-callback', function () {
    return view('wechat');
});

Route::get('/qq-login', function () {
    return Socialite::with('qq')->redirect();
})->name('qq.login');

Route::get('/qq-callback', function () {
    return view('qq');
});