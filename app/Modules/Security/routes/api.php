<?php

use App\Modules\Security\Http\Controllers\Api\v1\ApiAuthController;
use App\Modules\Security\Http\Controllers\Api\v1\OrganizationsController;
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
            Route::post('edit_user', [ApiAuthController::class, 'edit_user']);
        });

        Route::post('organizition_insert', [OrganizationsController::class, 'insert']);
        Route::get('get_organizition_list', [OrganizationsController::class, 'get_organizition_list']);
    }
);
