<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LessonhistoryController extends Controller
{
    public function show()
{
    $user = Auth::user();

    // Use app timezone to compute "past" (today + current time)
    $now = Carbon::now(); // config('app.timezone') を前提

    $bookings = Booking::query()
        ->select(['id', 'student_id', 'teacher_id', 'course_id', 'topic_id', 'date', 'time'])
        ->with([
            // limit columns per relation (perf)
            'course:id,title,image_url',
            'topic:id,course_id,name',
            'teacher:id,name',
            'report:booking_id,status,next_topic', // ← モーダル用に追加
        ])
        ->where('student_id', $user->id)
        ->where(function ($q) use ($now) {
            $today = $now->toDateString();      // 'YYYY-MM-DD'
            $nowT  = $now->format('H:i:s');     // 'HH:MM:SS'
            $q->where('date', '<', $today)
              ->orWhere(function ($q) use ($today, $nowT) {
                  $q->where('date', $today)
                    ->where('time', '<', $nowT);
              });
        })
        // date DESC, then time DESC (YYYY-MM-DD & HH:MM:SSなら文字列比較でもOK)
        ->orderByDesc('date')
        ->orderByDesc('time')
        ->paginate(10)
        ->appends(request()->query()); // keep query params on pagination

    // ビュー名をプロジェクト内で統一（例: student.lesson_history）
    return view('student.lessonhistory', compact('bookings'));
}
}
