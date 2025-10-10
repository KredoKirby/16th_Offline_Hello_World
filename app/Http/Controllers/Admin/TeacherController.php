<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Forum;

class TeacherController extends Controller
{
    public function index()
    {
        // 件数
        $studentsCount = Student::count();
        $teachersCount = Teacher::count();
        $coursesCount  = Course::count();
        $forumsCount   = Forum::count();

        // 直近データ
        $latestStudents = Student::latest()->select('id','name')->take(5)->get();
        $latestTeachers = Teacher::latest()->select('id','name')->take(5)->get();
        $latestCourses  = Course::latest()->select('id','name')->take(5)->get();

        // Forum はカラム直読み（course名/usernameが別テーブルなら後でwithに変更）
        $latestForums = Forum::latest()->select('id','question','course','username')->take(4)->get()
            ->map(fn($f) => [
                'question' => $f->question ?? 'Question',
                'course'   => $f->course   ?? 'Course',
                'username' => $f->username ?? 'Username',
            ]);

        return view('admin.teachers.index', compact(
            'studentsCount','teachersCount','coursesCount','forumsCount',
            'latestStudents','latestTeachers','latestCourses','latestForums'
        ));
    }
}
