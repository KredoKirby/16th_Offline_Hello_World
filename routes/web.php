<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;

// 入口：/ → /login
Route::get('/', fn () => redirect()->route('login'));

// ── Login & Register ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// ── 認証必須領域 ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // ── Admin ───────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/bootstrap', [AdminController::class, 'bootstrap'])->name('bootstrap');

        Route::get('/',             [AdminController::class, 'index'])->name('index');
        Route::get('/students',     [AdminController::class, 'students'])->name('students');
        Route::post('/students/{id}/toggle', [AdminController::class, 'studentToggle'])->name('students.toggle');

        Route::get('/teachers',     [AdminController::class, 'teachers'])->name('teachers');
        Route::get('/teachers/add', [AdminController::class, 'teacherAddForm'])->name('teachers.add.form');
        Route::post('/teachers/add',[AdminController::class, 'teacherAdd'])->name('teachers.add');
        Route::post('/teachers/{id}/toggle', [AdminController::class, 'teacherToggle'])->name('teachers.toggle');

        Route::get('/courses',      [AdminController::class, 'courses'])->name('courses');
        Route::post('/courses/{id}/toggle', [AdminController::class, 'courseToggle'])->name('courses.toggle');

        Route::get('/forums',       [AdminController::class, 'forums'])->name('forums');
    });
});
