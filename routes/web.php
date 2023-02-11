<?php

use Illuminate\Support\Facades\Route;

Route::get('{any}', \App\Http\Controllers\DefaultController::class)->where('any', '.*');
