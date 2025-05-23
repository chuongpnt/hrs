<?php

use App\Http\Controllers\FlightHeightController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flight-height-level-{level}', [FlightHeightController::class, 'flightHeightByLevel']);

