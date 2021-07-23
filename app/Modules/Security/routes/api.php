<?php

use App\Modules\Security\Http\Controllers\Api\v1\ApiAuthController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'Api\v1',
    ],
    function () {
        Route::post('login', [ApiAuthController::class, 'authenticate']);
        Route::post('register', [ApiAuthController::class, 'register']);

        Route::group(['middleware' => ['jwt.verify']], function () {
            Route::get('logout', [ApiAuthController::class, 'logout']);
            Route::get('get_user', [ApiAuthController::class, 'get_user']);
        });
    }
);
