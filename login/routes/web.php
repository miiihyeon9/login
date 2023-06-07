<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
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


// Route::get('user/registration',[UserController::class,'registration'])->name('user.registration');
Route::get('/users/login', [UserController::class, 'login'])->name('users.login');
Route::post('/users/registrationpost', [UserController::class, 'registrationpost'])->name('users.registration.post');
// Users
Route::get('/users/registration', [UserController::class, 'registration'])->name('users.registration');
Route::post('/users/registrationpost', [UserController::class, 'registrationpost'])->name('users.registration.post');

// 메일전송 TEST
Route::get('/mails/mail', [MailController::class, 'mail'])->name('mails.mail');
Route::post('/mails/mailpost', [MailController::class, 'mailpost'])->name('mails.mail.post');

// 메일인증 TEST
Route::get('/users/verify/{code}/{email}', [UserController::class, 'verify'])->name('users.verify');
Route::get('/resend-email', [UserController::class, 'resend_email'])->name('resend.email');