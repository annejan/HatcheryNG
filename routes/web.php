<?php

use App\Http\Controllers\BadgesController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ProjectsController;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('projects', ProjectsController::class, ['only' => ['index', 'show']]);
Route::any('search', [ProjectsController::class, 'index'])->name('projects.search');
Route::resource('badges', BadgesController::class, ['only' => ['index', 'show']]);
Route::resource('files', FilesController::class, ['only' => 'show']);
Route::get('download/{file}', [FilesController::class, 'download'])->name('files.download');
