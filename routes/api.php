<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



  // auth and logout
Route::post('/register',[AuthController::class , 'register'])->middleware('guest:sanctum');
Route::post('/auth-access-token' , [AuthController::class , 'store'])
->middleware('guest:sanctum');
 Route::delete('logout/{token?}', [AuthController::class, 'destroy'])
 ->middleware('auth:sanctum');

 // routes/api.php
Route::prefix('places')->group(function () {
    Route::get('/', [PlaceController::class, 'index']);
    Route::get('/search', [PlaceController::class, 'searchByName']); // البحث بالاسم
    Route::post('/', [PlaceController::class, 'store']);
});

 Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'show']); // للحصول على بيانات الملف الشخصي
    Route::put('/', [ProfileController::class, 'update']); // لتحديث الملف الشخصي
    Route::patch('/', [ProfileController::class, 'update']); // لتحديث جزئي
    Route::delete('/', [ProfileController::class, 'destroy']); // لحذف الحساب


});



