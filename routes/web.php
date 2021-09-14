<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::delete('/{city_id}/unfollow', [App\Http\Controllers\WeatherController::class, 'delete'])->name('unfollow')->middleware('auth');
Route::get('/follow', [App\Http\Controllers\WeatherController::class, 'add'])->name('follow')->middleware('auth');
Route::get('/', [App\Http\Controllers\WeatherController::class, 'index'])->name('home')->middleware('auth');
