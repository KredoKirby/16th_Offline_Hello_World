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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard & bootstrap
        Route::get('/bootstrap', [AdminController::class, 'bootstrap'])->name('bootstrap');
        Route::get('/', [AdminController::class, 'index'])->name('index');

        // ── Students ─────────────────────────
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::post('/students/{id}/toggle', [AdminController::class, 'studentToggle'])->name('students.toggle');

        // ── Teachers（明示ルートに統一） ─────
        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers.index');
        Route::get('/teachers/create', [AdminController::class, 'teacherAddForm'])->name('teachers.create');
        Route::post('/teachers', [AdminController::class, 'teacherAdd'])->name('teachers.store');
        Route::post('/teachers/{id}/toggle', [AdminController::class, 'teacherToggle'])->name('teachers.toggle');
        //  Teacher Edit
        Route::get('/teachers/{id}/edit', [AdminController::class, 'teacherEdit'])->name('teachers.edit');
        Route::put('/teachers/{id}', [AdminController::class, 'teacherUpdate'])->name('teachers.update');

        // ── Courses（show/edit/update あり） ─
        Route::get('/courses', [AdminController::class, 'courses'])->name('courses.index');           
        Route::get('/courses/create', [AdminController::class, 'courseAddForm'])->name('courses.create'); 
        Route::post('/courses', [AdminController::class, 'courseAdd'])->name('courses.store');           
        Route::get('/courses/{id}', [AdminController::class, 'courseShow'])->name('courses.show');        
        Route::get('/courses/{id}/edit', [AdminController::class, 'courseEdit'])->name('courses.edit');  
        Route::put('/courses/{id}', [AdminController::class, 'courseUpdate'])->name('courses.update');   
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
});