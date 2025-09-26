<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class SelfLearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 受講中のコース
        $myCourses = $user->courses()->with('sections.lessons')->get();

        // 完了したコース数（100% のもの）
        $completedCourses = $myCourses->filter(function ($course) use ($user) {
            return $course->completionRate($user->id) === 100;
        })->count();

        // 学習時間
        $hoursLearned =0;
        //  \DB::table('lesson_user')
        //     ->where('user_id', $user->id)
        //     ->sum('duration'); 

        // おすすめコース（ここでは仮に最新の3件を表示）
        $recommendedCourses = Course::latest()->take(3)->get();

        return view('selflearning.index', compact(
            'myCourses',
            'completedCourses',
            'hoursLearned',
            'recommendedCourses'
        ));
    }

     // 詳細
    public function show($id)
    {
        $course = Course::with('sections')->findOrFail($id);
        return view('selflearning.show', compact('course'));
    }
}
