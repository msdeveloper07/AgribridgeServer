<?php
use App\Modules\Security\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

Route::get('/security', [SecurityController::class, 'welcome']);
