<?php

use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\FlightHeightController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});





Route::get('/flight-height/{groupLevel}/{index}', [FlightHeightController::class, 'flightHeightByLevel']);
