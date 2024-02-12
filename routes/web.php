<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\RoleController;

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

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'action_login'])->name('actionlogin');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'action_register'])->name('actionregister');
Route::get('forgot-password', [AuthController::class, 'forgot_password'])->name('forgotpassword');
Route::post('forgot-password', [AuthController::class, 'action_forgot_password'])->name('actionforgotpassword');
Route::middleware(['auth'])->group(function () {
    Route::get('logout', [AuthController::class, 'action_logout'])->name('actionlogout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get("/users/serverside", [UsersController::class, 'serverside'])->name('users.serverside')->middleware('role:Administrator');
    Route::resource('users', UsersController::class)->middleware('role:Administrator');

    Route::get("/roles/serverside", [RoleController::class, 'serverside'])->name('roles.serverside');
    Route::resource('role', RoleController::class);
});
Route::get('/email/resetpassword', [VerificationController::class, 'resetPassword'])->name('verification.resetpassword');
Route::put('/email/resetpassword/{id}', [VerificationController::class, 'resetPasswordAction'])->name('verification.actionresetpassword');
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
