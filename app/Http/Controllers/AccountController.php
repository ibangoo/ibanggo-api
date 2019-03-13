<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function store()
    {
        return response()->json(['version' => config('api.version')]);
    }
}
