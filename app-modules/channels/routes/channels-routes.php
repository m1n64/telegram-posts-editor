<?php

use Modules\Channels\Http\Controllers\Api\ChannelsController;

Route::group(['prefix' => 'channels', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/create', [ChannelsController::class, 'create'])->name('api.channels.create');
    Route::delete('/delete/{id}', [ChannelsController::class, 'delete'])->name('api.channels.delete');
});
