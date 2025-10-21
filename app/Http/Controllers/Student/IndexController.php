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

        $courses = $user->courses()
        ->select('courses.id', 'courses.title')
        ->with(['topics:id,course_id,name'])
        // ->wherePivot('status', 'active')
        ->orderBy('courses.title')
        ->get();

        return view('student.index', compact('courses'));
    }
}
?>