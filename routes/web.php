<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\BlogViewController;
use App\Http\Controllers\Mypage\BlogMypageController;
use App\Http\Controllers\Mypage\UserLoginController;

Route::get('/', [BlogViewController::class, 'index']);
Route::get('blogs/{blog}', [BlogViewController::class, 'show']);

Route::get('signup', [SignUpController::class, 'index']);
Route::post('signup', [SignUpController::class, 'store']);

Route::get('mypage/login', [UserLoginController::class, 'index'])->name('login');
Route::post('mypage/login', [UserLoginController::class, 'login']);

Route::middleware('auth')->group(function () {
	Route::post('mypage/logout', [UserLoginController::class, 'logout']);
	Route::get('mypage/blogs', [BlogMypageController::class, 'index']);
});
