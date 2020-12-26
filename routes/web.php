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

Route::get('/', 'App\Http\Controllers\EventsController@shows')->name('welcome');
Route::get('/events/{showId}', 'App\Http\Controllers\EventsController@show')->name('events.partsevent');
Route::get('/events/{showId}/parts/{partId}', 'App\Http\Controllers\EventsController@reserve')->name('events.reserve');
Route::post('/parts/{partId}/reserve', 'App\Http\Controllers\EventsController@reserveAction')->name('events.reserve1');

