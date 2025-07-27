<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/profil-dusun', function () {
    return view('profil-dusun');
})->name('profil.dusun');

Route::get('/map', function () {
    return view('map');
})->name('map');

