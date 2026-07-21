<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — NutriShare
|--------------------------------------------------------------------------
| Routes will be populated in Step 5: Controllers & Views
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
