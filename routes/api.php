<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ShiftKerjaController;

Route::post('login',            [AuthController::class, 'login']);
Route::post('password-reset',   [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('pegawai',       PegawaiController::class);
    Route::apiResource('shift',         ShiftKerjaController::class);
    Route::apiResource('gaji',          GajiController::class);
    Route::apiResource('payroll',       PayrollController::class);
    
    Route::get('kehadiran/get-kode',    [KehadiranController::class, 'generateKeyAbsensi']);
    
    Route::middleware('admin')->group(function () {
        Route::post('kehadiran/confirm',        [KehadiranController::class, 'confirmAbsensi']);
        Route::post('email/send-verification',  [EmailController::class, 'sendEmailVerification']);
    });

    Route::post('logout',   [AuthController::class, 'logout']);
});
