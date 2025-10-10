<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\CourseController  as AdminCourseController;
use App\Http\Controllers\Admin\ForumController   as AdminForumController;

// Front/controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\Student\MylearningController;
use App\Http\Controllers\Student\LessonhistoryController;
use App\Http\Controllers\Student\IndexController  as StudentIndexController;
use App\Http\Controllers\Teacher\IndexController  as TeacherIndexController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\SelfLearningController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // all
    Route::get('/', function () {
        $role = Auth::user()->role_id;
        return match ($role) {
            1 => redirect()->route('admin.dashboard'),   // admin
            2 => redirect()->route('teacher.home'),      // teacher
            3 => redirect()->route('student.home'),      // student
            4 => redirect()->route('courses.index'),     // user
        };
    })->name('home');

    /* ------------------- Admin ------------------- */
    Route::prefix('admin')->middleware('can:admin')->name('admin.')->group(function () {
        // ダッシュボード
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // 一覧・CRUD
        Route::resource('students', AdminStudentController::class)->names('students');
        Route::resource('teachers', AdminTeacherController::class)->names('teachers');
        Route::resource('courses',  AdminCourseController::class )->names('courses');
        Route::resource('forums',   AdminForumController::class  )->names('forums');

        // 追加のアクション
        Route::patch('teachers/{teacher}/toggle', [AdminTeacherController::class, 'toggle'])
            ->name('teachers.toggle');
        Route::patch('courses/{course}/toggle', [AdminCourseController::class, 'toggle'])
            ->name('courses.toggle');
    });

    /* ------------------- Student area ------------------- */
    Route::prefix('students')->middleware('can:students')->name('student.')->group(function () {
        Route::get('/', [StudentIndexController::class, 'index'])->name('home');
        Route::get('mylearning',      [MylearningController::class, 'show'])->name('mylearning');
        Route::get('lesson_history',  [LessonhistoryController::class, 'show'])->name('lessonhistory');
        Route::get('profile',         [StudentProfileController::class, 'show'])->name('profile');
    });

    /* ------------------- Teacher area ------------------- */
    Route::prefix('teachers')->middleware('can:teachers')->name('teacher.')->group(function () {
        Route::get('/', [TeacherIndexController::class, 'index'])->name('home');
        Route::get('profile', [TeacherProfileController::class, 'show'])->name('profile');
    });

    /* ------------------- Public courses ------------------- */
    Route::get('/courses',                           [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}',                  [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/enroll',          [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll',      [CourseController::class, 'unenroll'])->name('courses.unenroll');
    Route::post('/lessons/{lesson}/progress',        [LessonController::class, 'updateProgress'])->name('lessons.updateProgress');
    Route::post('/courses/{course}/lessons/{lesson}/toggle', [LessonController::class, 'toggle'])->name('lessons.toggle');

    /* ------------------- SelfLearning ------------------- */
    Route::prefix('selflearning')->name('selflearning.')->group(function () {
        Route::get('/',     [SelfLearningController::class, 'index'])->name('index');
        Route::get('/{id}', [SelfLearningController::class, 'show'])->name('show');
    });
});