<?php

use App\Http\Controllers\DisheController;
use Illuminate\Support\Facades\Route;

Route::apiResource('dishes', DisheController::class);
