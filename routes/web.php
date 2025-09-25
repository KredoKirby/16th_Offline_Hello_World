<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\Student\IndexController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\MylearningController;
use App\Http\Controllers\Student\LessonhistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
//All
Auth::routes();

// ── Authenticated Routes ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // ── Admin ───────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/bootstrap', [AdminController::class, 'bootstrap'])->name('bootstrap');

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::post('/students/{id}/toggle', [AdminController::class, 'studentToggle'])->name('students.toggle');

        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
        Route::get('/teachers/add', [AdminController::class, 'teacherAddForm'])->name('teachers.add.form');
        Route::post('/teachers/add', [AdminController::class, 'teacherAdd'])->name('teachers.add');
        Route::post('/teachers/{id}/toggle', [AdminController::class, 'teacherToggle'])->name('teachers.toggle');

        Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
        Route::post('/courses/{id}/toggle', [AdminController::class, 'courseToggle'])->name('courses.toggle');
      
        // 追加フォーム表示 & 保存 course
        Route::get('/courses/create', [AdminController::class, 'courseAddForm'])->name('courses.create');
        Route::post('/courses', [AdminController::class, 'courseAdd'])->name('courses.store');

        Route::get('/forums', [AdminController::class, 'forums'])->name('forums');

        // Teachers: Add 追加フォーム & 保存
        Route::get('/teachers/add', [AdminController::class, 'teacherAddForm'])->name('teachers.add.form');
        Route::post('/teachers/add', [AdminController::class, 'teacherAdd'])->name('teachers.add');

        // Courses: Add 追加フォーム & 保存
        Route::get('/courses/create', [AdminController::class, 'courseAddForm'])->name('courses.create');
        Route::post('/courses', [AdminController::class, 'courseAdd'])->name('courses.store');

    });
  
    // Student
    Route::prefix('student')->group(function () {
        Route::get('index', [IndexController::class, 'index'])->name('student.index');
        Route::get('mylearning', [MylearningController::class, 'show'])->name('student.mylearning');
        Route::get('lesson_history', [LessonhistoryController::class, 'show'])->name('student.lessonhistory');
        Route::get('profile', [ProfileController::class, 'show'])->name('student.profile');
    });
  
    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])
        ->name('lessons.updateProgress');
    Route::post('/courses/{course}/lessons/{lesson}/toggle', [LessonController::class, 'toggle'])
        ->name('lessons.toggle');
    Route::delete('/courses/{course}/unenroll', [CourseController::class, 'unenroll'])
     ->name('courses.unenroll');

});