<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role_id', 3)
            ->with(['courses:id,title'])   // ← title のみ
            ->withCount('courses')         // ← 件数取得
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }
}
