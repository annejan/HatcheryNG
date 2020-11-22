<?php

use App\Http\Controllers\WeatherController;

Route::get('/', [WeatherController::class, 'show'])->name('weather');
Route::get('{location}', [WeatherController::class, 'location'])->name('weather.location');
