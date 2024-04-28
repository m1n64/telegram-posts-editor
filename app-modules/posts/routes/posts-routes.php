<?php


use Modules\Posts\Http\Controllers\Api\PostsController;

Route::group(['prefix' => 'posts', 'middleware' => ['auth:sanctum', 'api.check.telegram.access']], function () {
    Route::post('/save', [PostsController::class, 'save'])->name('api.posts.save');
    Route::post('/send', [PostsController::class, 'send'])->name('api.posts.send');
    Route::post('/schedule', [PostsController::class, 'schedule'])->name('api.posts.schedule');
});
