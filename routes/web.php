<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\IndexController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\MylearningController;
use App\Http\Controllers\Student\LessonhistoryController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Student
Route::prefix('student')->group(function () {
    Route::get('index', [IndexController::class, 'index'])->name('student.index');
    Route::get('mylearning', [MylearningController::class, 'show'])->name('student.mylearning');
    Route::get('lesson_history', [LessonhistoryController::class, 'show'])->name('student.lessonhistory');
    Route::get('profile', [ProfileController::class, 'show'])->name('student.profile');
});