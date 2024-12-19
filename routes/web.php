<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseProgressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ResponseController;

Route::middleware(['isNotLogin'])->group(function () {
    Route::get('/', [UserController::class, 'login'])->name('login');
    Route::post('/login/store',[UserController::class,'loginAuth'])->name('loginAuth');
    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register/store', [UserController::class, 'createUser'])->name('register.store');  
});


Route::middleware(['isLogin'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/home', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['isHeadStaff'])->group(function () {
    Route::get('/home/akun', [UserController::class, 'index'])->name('home.akun');
    Route::get('/create/akun', [UserController::class, 'create'])->name('user.create');
    Route::post('/store/akun', [UserController::class, 'store'])->name('user.store');
    // Route::get('/edit/akun/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/reset/akun/{id}', [UserController::class, 'reset'])->name('user.reset');
    Route::delete('/delete/akun/{id}', [UserController::class, 'destroy'])->name('user.delete');

});

Route::middleware(['isStaff'])->group(function(){
    Route::get('/response', [ResponseController::class, 'index'])->name('response');
Route::get('/response/detail/{id}', [ResponseController::class, 'show'])->name('response.show');
Route::post('/response/store/{id}', [ResponseController::class, 'store'])->name('response.status');
Route::patch('/response/update/{id}', [ResponseController::class, 'update'])->name('response.update.status');
Route::post('/response/progres/{id}', [ResponseProgressController::class, 'store'])->name('response.progress');
    Route::get('/export-reports', [ReportController::class, 'export'])->name('report.export');
});

Route::middleware(['isGuest'])->group(function(){
    Route::get('/report/detail/{id}', [ReportController::class, 'show'])->name('report.show');

Route::resource('report', ReportController::class)->only(['create', 'store','index']);

// Route::post('/report/store', [ReportController::class, 'store'])->name('report.store');
Route::post('/report/{id}/vote', [ReportController::class, 'voting'])->name('report.vote');

Route::post('/report/{id}/store-comment', [CommentController::class, 'store'])->name('report.comment');
Route::get('/report/monitor/me', [ReportController::class, 'monitor'])->name('report.monitor');
Route::delete('/report/delete/{id}', [ReportController::class, 'destroy'])->name('report.destroy');
});

// Route untuk menampilkan daftar laporan
// Route::get('/report', [ReportController::class, 'index'])->name('report.index');

// Route untuk menampilkan form create laporan (Harus diletakkan sebelum {id})
// Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');

// Route untuk menampilkan detail laporan berdasarkan ID







});


