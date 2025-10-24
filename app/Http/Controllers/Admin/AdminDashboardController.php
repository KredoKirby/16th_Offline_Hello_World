<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;           // ← 追加
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 既存：Users から Students / Teachers の集計
        $studentsCount = User::where('role_id', 3)->count();
        $teachersCount = User::where('role_id', 2)->count();

        // 重要：Courses は courses テーブルから
        $coursesCount = Course::count();

        $forumsCount = 0;

        // Students の最新5件（名前）
        $latestStudents = User::where('role_id', 3)
            ->orderByDesc('id')->take(5)->get(['name']);

        // Teachers の最新5件（名前）
        $latestTeachers = User::where('role_id', 2)
            ->orderByDesc('id')->take(5)->get(['name']);

        // Courses の最新5件（タイトル）
        $latestCourses = Course::orderByDesc('id')
            //修正
            ->limit(5)
            ->pluck('title');
        // ->take(5)->get(['title']);修正前

        return view('admin.index', compact(
            'studentsCount',
            'teachersCount',
            'coursesCount',
            'forumsCount',
            'latestStudents',
            'latestTeachers',
            'latestCourses'
        ));
    }

    // Courses ページ用：全件（必要ならpaginateに変更OK）
    public function courses()
    {
        $items = \App\Models\Course::orderByDesc('id')->get(['title', 'description', 'language', 'level']);
        return view('admin.courses', compact('items'));
        // $items = Course::orderByDesc('id')->get(['id','title','description','level','language','image']);
        // return view('admin.courses', compact('items'));
    }

    // 参考：students/teachers ページ
    public function students()
    {
        $items = User::where('role_id', 3)->orderByDesc('id')->get(['name', 'email']);
        return view('admin.students', compact('items'));
    }
    public function teachers()
    {
        $items = User::where('role_id', 2)->orderByDesc('id')->get(['name', 'email']);
        return view('admin.teachers', compact('items'));
    }
}
