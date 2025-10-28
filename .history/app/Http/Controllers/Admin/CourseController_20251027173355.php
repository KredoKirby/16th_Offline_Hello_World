<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.courses.index', compact('courses'));
    }


public function create()
{
    // 新規コース作成フォームを表示
    return view('admin.courses.create'); // Bladeの場所に合わせてパスを変更
}

}
