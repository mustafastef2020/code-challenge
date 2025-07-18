<?php

use App\Enums\Currency;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'login');

Route::view('/quotation-form', 'quotations.create', ['currencies' => Currency::cases()])->name('quotation.form');
