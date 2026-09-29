<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;

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

Route::get('/', function () { return redirect()->route('login');});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/dashboard', function () { return view('dashboard.index');})->middleware('auth')->name('dashboard');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/managementUser', [UserController::class, 'index']) ->name('managementUser.index');
    Route::post('/managementUser', [UserController::class, 'store']) ->name('managementUser.store');
    Route::get('/managementUser/{user}', [UserController::class, 'show']) ->name('managementUser.show');
    Route::put('/managementUser/{user}', [UserController::class, 'update']) ->name('managementUser.update');
    Route::delete('/managementUser/{user}', [UserController::class, 'destroy']) ->name('managementUser.destroy'); 
    Route::get('/program-utilization', function () { return view('programUtilization.index');})->name('programUtilization.index');
    Route::get('/anomaly-log', function () { return view('anomalyLog.index');})->name('anomalyLog.index');
    Route::get('/access-matrix', function () { return view('accessMatrix.index');})->name('accessMatrix.index');
});
