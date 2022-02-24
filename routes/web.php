<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogViewController;

Route::get('/', [BlogViewController::class, 'index']);
Route::get('blogs/{blog}', [BlogViewController::class, 'show']);
