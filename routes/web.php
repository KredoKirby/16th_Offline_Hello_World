<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\IndexController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Student
Route::prefix('student')->name('student.')->group(function () {
    // /student/index → student.index
    Route::get('index', [IndexController::class, 'index'])->name('index');
});