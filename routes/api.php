<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [UserController::class, 'login']);
Route::get('/user/check', [UserController::class, 'checkUser'])->middleware('auth:sanctum');
Route::get('/user/{email}', [UserController::class, 'index']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::put('/user/googleid/{id}', [UserController::class, 'updateGoogleId']);
Route::post('/user', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
