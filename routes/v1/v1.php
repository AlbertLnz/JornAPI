<?php

use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Controllers\v1\Auth\LogOutController;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Http\Controllers\v1\Employee\UpdateEmployeeController;
use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Controllers\v1\user\DeleteUserController;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Http\Controllers\v1\User\UserUpdateController;
use App\Services\User\UserUpdateService;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterEmployeeController::class);
Route::post('/login', LoginController::class);



Route::middleware(['jwt.auth','token_redis', 'role:employee','is_active'])->group(function(){
    Route::get('/employee', ShowEmployeeController::class);
    Route::put('/employee', UpdateEmployeeController::class);
    Route::put('/user/update', UserUpdateController::class);
    Route::get('/user/show', ShowUserController::class);
    Route::post('user/delete',DeleteUserController::class );
    Route::post('/logout',LogOutController::class);
});

Route::get('/hola', function(){
return "hola";
})->middleware('token_redis');

