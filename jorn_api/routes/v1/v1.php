<?php

use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Controllers\v1\Auth\LogOutController;
use App\Http\Controllers\v1\Dashboard\DashboardController;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Http\Controllers\v1\Employee\UpdateEmployeeController;
use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Controllers\v1\HourSession\DeleteHourSession\HourSessionDeleteController;
use App\Http\Controllers\v1\HourSession\RegisterHourSession\HourSessionRegisterController;
use App\Http\Controllers\v1\HourSession\ShowHourSession\HourSessionShowAllController;
use App\Http\Controllers\v1\HourSession\ShowHourSession\HourSessionShowController;
use App\Http\Controllers\v1\HourSession\UpdateHourSession\HourSessionUpdateController;
use App\Http\Controllers\v1\Salary\ShowSalaryController;
use App\Http\Controllers\v1\User\DeleteUserController;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Http\Controllers\v1\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterEmployeeController::class)->name('verification.verify');
Route::post('/login', LoginController::class);



Route::middleware(['jwt.auth','token_redis', 'role:employee','is_active'])->group(function(){
    Route::get('/employee', ShowEmployeeController::class);
    Route::put('/employee', UpdateEmployeeController::class);
    Route::put('/user/update', UserUpdateController::class);
    Route::get('/user/show', ShowUserController::class);
    Route::post('user/delete', DeleteUserController::class );
    Route::post('/logout',LogOutController::class);

    Route::post('/hourSession', HourSessionRegisterController::class);
    Route::get('/hourSession', HourSessionShowController::class);
    Route::put('/hourSession', HourSessionUpdateController::class);
    Route::get('/hourSession/all', HourSessionShowAllController::class);
    Route::delete('/hourSession/delete', HourSessionDeleteController::class);

    Route::get('/dashboard', DashboardController::class);

    Route::get('/salary', ShowSalaryController::class);
});

Route::get('/hola', function(){
return "hola";
});

Route::get('/employee/show', function () {
    // Datos hardcodeados para la demostración
    $employeeData = [
        'name' => 'Rebeca Sueiro',
        'company' => 'HotmessWorld',
        'normalRate' => 25.50,
        'overtimeRate' => 38.75,
        'nightRate' => 30.00,
        'holidayRate' => 40.00,
        'irpf' => 15,
    ];

    // Devolver los datos hardcodeados en una respuesta JSON
    return response()->json($employeeData, 200);
});

Route::get('/time',TimeEntryController::class);