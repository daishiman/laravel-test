<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogViewController;

Route::get('/', [BlogViewController::class, 'index']);
