<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\DeviceController;

use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\InternalWebhookController;

Route::post('/internal/webhook/receive', [InternalWebhookController::class, 'receive']);

Route::middleware('api.auth')->prefix('v1')->group(function () {
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages', [MessageController::class, 'index']);
    
    Route::get('/devices', [DeviceController::class, 'index']);
    
    Route::get('/usage', function (Request $request) {
        return response()->json(['usage' => 'Not implemented yet']);
    });
    
    Route::post('/otp/send', [OtpController::class, 'send']);
    Route::post('/otp/verify', [OtpController::class, 'verify']);
});
