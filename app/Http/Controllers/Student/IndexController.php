<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
         /** @var \App\Models\User $user */
        $user = Auth::user();

        // enrollments を pivot にした多対多から取得
        $courses = $user->courses()                 // User::courses() は belongsToMany(...)
            ->select('courses.id', 'courses.title') // 必要な列だけ
            // ->wherePivot('status', 'active')     // 必要なら受講中のみ
            ->orderBy('courses.title')
            ->get();

        return view('student.index', compact('courses'));
    }
}
?>