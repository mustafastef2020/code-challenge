<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QuotationController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/quotation', [QuotationController::class, 'store'])->name('quotation.store')->middleware('auth:api');