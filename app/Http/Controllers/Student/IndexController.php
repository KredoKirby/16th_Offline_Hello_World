<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Booking;
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

        // Up next booking（直近の予約1件）を取得追加
        $now     = Carbon::now();
        $nowDate = $now->toDateString();      // '2025-10-27'
        $nowTime = $now->format('H:i:s');     // '14:08:00'

        $upNext = Booking::with(['course', 'topic', 'teacher'])
            ->where('student_id', $user->id)  // ←教師表示に変えたい場合は teacher_id に変更可
            ->where(function ($q) use ($nowDate, $nowTime) {
                $q->where('date', '>', $nowDate)
                ->orWhere(function ($q) use ($nowDate, $nowTime) {
                    $q->where('date', $nowDate)
                        ->where('time', '>=', $nowTime);
                });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        // === Lesson history（直近の過去レッスン3件を取得・追加） ===
        $history = Booking::with([
        'course:id,title,image_url',
        'topic:id,course_id,name',
        'teacher:id,name',
        'report:booking_id,status,next_topic',
        ])
        ->where('student_id', $user->id)
        ->where(function ($q) use ($nowDate, $nowTime) {
            $q->where('date', '<', $nowDate)
            ->orWhere(function ($q) use ($nowDate, $nowTime) {
                $q->where('date', $nowDate)->where('time', '<', $nowTime);
            });
        })
        ->orderByDesc('date')->orderByDesc('time')
        ->limit(3)
        ->get();

        return view('student.index', compact('courses', 'upNext', 'history'));
    }
}
?>