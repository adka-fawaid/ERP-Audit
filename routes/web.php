<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\ProgramUtilizationController;
use App\Http\Controllers\Pages\AnomalyLogController;
use App\Http\Controllers\Pages\MatrixController;
use App\Http\Controllers\Pages\ActivityLogController;
use App\Http\Controllers\Pages\ReportExportController;


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
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']) ->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index']) ->name('dashboard');
    Route::get('/program-utilization', [ProgramUtilizationController::class, 'index']) ->name('programUtilization.index');
    Route::get('/anomaly-log', [AnomalyLogController::class, 'index'])->name('anomalyLog.index');
    Route::get('/access-matrix', [MatrixController::class, 'index'])->name('accessMatrix.index');
    Route::post('/dashboard/export', [ReportExportController::class, 'dashboard'])->name('dashboard.export');
    Route::post('/program-utilization/export', [ReportExportController::class, 'programUtilization'])->name('programUtilization.export');
    Route::post('/anomaly-log/export', [ReportExportController::class, 'anomalyLog'])->name('anomalyLog.export');
    Route::post('/access-matrix/export', [ReportExportController::class, 'accessMatrix'])->name('accessMatrix.export');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/managementUser', [UserController::class, 'index']) ->name('managementUser.index');
    Route::post('/managementUser', [UserController::class, 'store']) ->name('managementUser.store');
    Route::get('/managementUser/{user}', [UserController::class, 'show']) ->name('managementUser.show');
    Route::put('/managementUser/{user}', [UserController::class, 'update']) ->name('managementUser.update');
    Route::delete('/managementUser/{user}', [UserController::class, 'destroy']) ->name('managementUser.destroy'); 
    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('logActivity.index');
    Route::post('/log-aktivitas/export', [ReportExportController::class, 'activityLog'])->name('logActivity.export');
});
