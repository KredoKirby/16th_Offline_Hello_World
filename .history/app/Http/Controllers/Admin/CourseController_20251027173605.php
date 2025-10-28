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

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'price' => 'nullable|numeric',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:100',
        'language' => 'required|string',
        'level' => 'required|string',
        'image' => 'nullable|string', // Base64
    ]);

    $course = new Course();
    $course->title = $request->title;
    $course->price = $request->price ?? 0;
    $course->description = $request->description;
    $course->category = $request->category;
    $course->language = $request->language;
    $course->level = $request->level;

    // Base64画像を保存
    if ($request->image) {
        $imageData = $request->image;
        $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageName = time() . '.png';
        \File::put(public_path('images/courses/' . $imageName), base64_decode($imageData));
        $course->image = $imageName;
    }

    $course->save();

    return redirect()->route('admin.courses')->with('success', 'Course created successfully!');
}



    public function create()
{
    // 新規コース作成フォームを表示
    return view('admin.courses.create'); // Bladeの場所に合わせてパスを変更
}

}
