<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * 一覧表示
     */
    public function index()
    {
        $courses = \App\Models\Course::with(['lessons'])->get();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * 新規作成フォーム
     */
    public function create()
    {
        return view('admin.courses.create'); // Bladeの場所に合わせて
    }

    /**
     * 保存処理
     */
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

        // Base64画像をStorageに保存
        if ($request->image) {
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = time() . '.png';

            // storage/app/public/courses に保存
            Storage::disk('public')->put('courses/' . $imageName, base64_decode($imageData));
            $course->image = 'courses/' . $imageName;
        }

        $course->save();

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully!');
    }

    /**
     * 編集フォーム
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Course $course)
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

        $course->title = $request->title;
        $course->price = $request->price ?? 0;
        $course->description = $request->description;
        $course->category = $request->category;
        $course->language = $request->language;
        $course->level = $request->level;

        if ($request->image) {
            // 古い画像を削除
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }

            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->image);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = time() . '.png';

            Storage::disk('public')->put('courses/' . $imageName, base64_decode($imageData));
            $course->image = 'courses/' . $imageName;
        }

        $course->save();

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully!');
    }

    /**
     * 削除処理
     */
    public function destroy(Course $course)
    {
        // 画像も削除
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully!');
    // ★ 追加（ここが重要！）★
    public function show($id)
    {
        // topics を一緒に取る
        $course = Course::with('topics')->findOrFail($id);

        return view('admin.courses.show', compact('course'));
    }
}
