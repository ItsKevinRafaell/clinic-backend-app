<?php

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\OrderController;
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

Route::get('/doctors/active', [DoctorController::class, 'getDoctorActive']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::post('/doctors', [DoctorController::class, 'store']);
    Route::put('/doctors/{id}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']);
    Route::get('/doctors/search', [DoctorController::class, 'searchDoctor']);
    Route::get('/doctors/clinic/{id}', [DoctorController::class, 'getDoctorByClinic']);
    Route::get('/doctors/specialist/{id}', [DoctorController::class, 'getDoctorBySpecialist']);
    Route::get('/doctors/{id}', [DoctorController::class, 'getDoctorById']);


    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/patient/{patient_id}', [OrderController::class, 'getOrderByPatient']);
    Route::get('/orders/doctor/{doctor_id}', [OrderController::class, 'getOrderByDoctor']);
    Route::get('/orders/clinic/{clinic_id}', [OrderController::class, 'getOrderByClinic']);
    Route::get('/orders/summary/{clinic_id}', [OrderController::class, 'getSummary']);
    Route::post('/xendit-callback', [OrderController::class, 'handleCallback']);
});

