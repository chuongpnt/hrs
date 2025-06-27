<?php

use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/_test', function () {
    return response()->json(['msg' => 'api.php is working']);
});

Route::prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'index']);
    Route::post('/', [StaffController::class, 'store']);
    Route::get('{id}', [StaffController::class, 'show']);
    Route::put('{id}', [StaffController::class, 'update']);
    Route::delete('{id}', [StaffController::class, 'destroy']);
});
