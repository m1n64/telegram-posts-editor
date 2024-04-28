<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require base_path() . '/app-modules/channels/routes/channels-routes.php';
require base_path() . '/app-modules/posts/routes/posts-routes.php';
