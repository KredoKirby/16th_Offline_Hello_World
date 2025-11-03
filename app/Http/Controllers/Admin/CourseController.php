<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = \App\Models\Course::with(['lessons'])->get();
        return view('admin.courses.index', compact('courses'));
    }

    // ★ 追加（ここが重要！）★
    public function show($id)
    {
        // topics を一緒に取る
        $course = Course::with('topics')->findOrFail($id);

        return view('admin.courses.show', compact('course'));
    }
}
