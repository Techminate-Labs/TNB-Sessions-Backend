<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/symlink', function () {
    Artisan::call('storage:link');
});

Route::get('/clrcache', function () {
    Artisan::call('cache:clear');
});

Route::get('/clrconf', function () {
    Artisan::call('config:clear');
});

Route::get('/optimizeClr', function () {
    Artisan::call('optimize:clear');
});

Route::get('/optimize', function () {
    Artisan::call('optimize');
});