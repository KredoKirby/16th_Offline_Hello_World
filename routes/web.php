<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SelfLearningController;

// Front/controllers
use App\Http\Controllers\Student\MylearningController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Student\LessonhistoryController;
use App\Http\Controllers\Admin\ForumController   as AdminForumController;
use App\Http\Controllers\Admin\CourseController  as AdminCourseController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Student\IndexController  as StudentIndexController;
use App\Http\Controllers\Teacher\IndexController  as TeacherIndexController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\TopicApiController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // all
    Route::get('/', function () {
        $role = Auth::user()->role_id;
        return match ($role) {
            1 => redirect()->route('admin.index'), // admin
            2 => redirect()->route('teachers.index'), // teacher
            3 => redirect()->route('students.index'), // student
            4 => redirect()->route('courses.index'), // basic_user
        };
    })->name('home');

    /* ------------------- Admin ------------------- */
    Route::prefix('admin')->middleware('can:admin')->name('admin.')->group(function () {
        // ダッシュボード
        Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

        // 一覧・CRUD
        Route::resource('students', AdminStudentController::class)->names('students');
        Route::resource('teachers', AdminTeacherController::class)->names('teachers');
        Route::resource('courses',  AdminCourseController::class )->names('courses');
        Route::resource('forums',   AdminForumController::class  )->names('forums');

        // ── Teachers（明示ルートに統一） ─────
        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers.index');
        Route::get('/teachers/create', [AdminController::class, 'teacherAddForm'])->name('teachers.create');
        Route::post('/teachers', [AdminController::class, 'teacherAdd'])->name('teachers.store');
        Route::post('/teachers/{id}/toggle', [AdminController::class, 'teacherToggle'])->name('teachers.toggle');
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

        // 追加のアクション
        Route::patch('teachers/{teacher}/toggle', [AdminTeacherController::class, 'toggle'])
            ->name('teachers.toggle');
        Route::patch('courses/{course}/toggle', [AdminCourseController::class, 'toggle'])
            ->name('courses.toggle');
    });

    // Courses
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::post('/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
        Route::delete('/{course}/unenroll', [CourseController::class, 'unenroll'])->name('courses.unenroll');
        Route::post('/{course}/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])
            ->name('lessons.updateProgress');
        Route::post('/{course}/lessons/{lesson}/toggle', [LessonController::class, 'toggle'])
            ->name('lessons.toggle');
    });

    //  Self-learning
   Route::prefix('selflearning')->group(function () {
        Route::get('/', [SelfLearningController::class, 'index'])->name('selflearning.index');
        Route::get('/{id}', [SelfLearningController::class, 'show'])->name('selflearning.show');
        Route::get('/{courseId}/lesson/{lessonId}', [SelfLearningController::class, 'lessonVideo'])
            ->name('selflearning.lessonVideo');
        Route::post('/{courseId}/lesson/{lessonId}/done', [SelfLearningController::class, 'lessonDone'])
            ->name('selflearning.lesson.done');
        Route::get('/{courseId}/lesson/{lessonId}/text', [SelfLearningController::class, 'lessonText'])
            ->name('selflearning.lesson.text');
        Route::post('/{courseId}/lesson/{lessonId}/toggle', [SelfLearningController::class, 'toggleLesson'])       
            ->name('selflearning.lesson.toggle');
        Route::post('/update-time', [SelfLearningController::class, 'updateStudyTime'])
            ->name('selflearning.updateTime');
    });

    /* ------------------- Student area ------------------- */
    Route::prefix('students')->middleware('can:students')->name('students.')->group(function () {
        Route::get('/', [StudentIndexController::class, 'index'])->name('index');
        Route::get('mylearning',      [MylearningController::class, 'show'])->name('mylearning');
        Route::get('lesson_history',  [LessonhistoryController::class, 'show'])->name('lessonhistory');
        Route::get('profile',         [StudentProfileController::class, 'show'])->name('profile');

        // 予約フォーム表示 / 保存（既存）
        Route::get('/bookings/create', [StudentBookingController::class, 'create'])
            ->name('bookings.create');
        Route::post('/bookings', [StudentBookingController::class, 'store'])
            ->name('bookings.store');

        // Ajax: 指定コースのトピック一覧 + 「次のTopic」候補（JSON）
        // Route::get('/api/courses/{course}/topics', [TopicApiController::class, 'byCourse'])
        //     ->name('api.topics.byCourse');

        Route::get(
        '/api/courses/{course}/topics', [TopicApiController::class, 'byCourse'])->name('api.topics.byCourse');
        });

    /* ------------------- Teacher area ------------------- */
    Route::prefix('teachers')->middleware('can:teachers')->name('teachers.')->group(function () {
        Route::get('/', [TeacherIndexController::class, 'index'])->name('index');
        Route::get('profile', [TeacherProfileController::class, 'show'])->name('profile');
        Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    });
});