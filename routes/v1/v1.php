<?php

use App\Http\Controllers\v1\RegisterEmployeeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterEmployeeController::class);