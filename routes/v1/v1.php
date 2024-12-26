<?php

use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Controllers\v1\Auth\LogOutController;
use App\Http\Controllers\v1\DashBoard\DashboardController;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Http\Controllers\v1\Employee\UpdateEmployeeController;
use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Controllers\v1\HourSession\DeleteHourSession\HourSessionDeleteController;
use App\Http\Controllers\v1\HourSession\RegisterHourSession\HourSessionRegisterController;
use App\Http\Controllers\v1\HourSession\ShowHourSession\HourSessionShowController;
use App\Http\Controllers\v1\HourSession\UpdateHourSession\HourSessionUpdateController;
use App\Http\Controllers\v1\Salary\ShowSalaryByMonthController;
use App\Http\Controllers\v1\User\DeleteUserController;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Http\Controllers\v1\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterEmployeeController::class)->name('verification.verify');
Route::post('/login', LoginController::class);



Route::middleware(['jwt.auth','token_redis', 'role:employee','is_active'])->group(function(){
    //User Routes
    Route::put('/user/update', UserUpdateController::class);
    Route::get('/user/show', ShowUserController::class);
    Route::post('user/delete', DeleteUserController::class );
    //Employee Routes
    Route::get('/employee', ShowEmployeeController::class);
    Route::put('/employee', UpdateEmployeeController::class);
   
  //HourSession Routes
  Route::post('/hour_session', HourSessionRegisterController::class);  // Crear
  Route::get('/hour_session', HourSessionShowController::class);       // Leer
  Route::put('/hour_session', HourSessionUpdateController::class);     // Actualizar
  Route::delete('/hour_session', HourSessionDeleteController::class);  // Eliminar
  

    Route::get('/dashboard', DashboardController::class);

   //Salary Routes
   Route::get('/salary', ShowSalaryByMonthController::class);

   Route::post('/logout',LogOutController::class);

});




