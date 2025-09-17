<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{

public function index(Request $request)
{
    $courses = Course::all();

    // デフォルトは空配列
    $enrolledCourseIds = [];

    // ログインしている場合のみ取得
    if (auth()->check()) {
        $enrolledCourseIds = auth()->user()->enrollments()->pluck('course_id')->toArray();
    }

    $selectedCourse = null;
    if ($request->has('course')) {
        $selectedCourse = Course::find($request->course);
    }

    return view('courses.index', compact('courses', 'enrolledCourseIds', 'selectedCourse'));
}





    public function show($id)
    {
        $course = Course::with('sections.lessons')->findOrFail($id);
        $user = auth()->user();

        // enroll済みかどうかを判定
        $isEnrolled = $user 
            ? $course->enrollments()->where('user_id', $user->id)->exists()
            : false;

        // コース一覧も右サイドで必要なので取得
        $courses = Course::all();

        return view('courses.show', compact('course', 'isEnrolled', 'courses'));
    }
}
