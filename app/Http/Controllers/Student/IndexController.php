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
        $tz   = config('app.timezone', 'Asia/Tokyo');

        $courses = $user->courses() // teacher_course tableからteacherに紐づくcourseを取得
        ->select('courses.id', 'courses.title')
        ->with(['topics:id,course_id,name']) // topics tableから、courseに紐づくrecordを事前に取得
        // ->wherePivot('status', 'active')
        ->orderBy('courses.title')
        ->get();

        // Up next booking（直近の予約1件）を取得追加
        $now     = Carbon::now($tz); // 日時を取得
        $nowDate = $now->toDateString();      // '2025-10-27'　日時のみ
        $nowTime = $now->format('H:i:s');     // '14:08:00'　時間のみ

        $upNext = Booking::with(['course', 'topic', 'teacher']) // booking modelの中に定義されているmethodを指定してeager loading
            ->where('student_id', $user->id)  // ←教師表示に変えたい場合は teacher_id に変更可
            ->where(function ($q) use ($nowDate, $nowTime) {
                // dd($q);
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

        // ▼ ここから FullCalendar 用イベント配列を生成
$bookingsForCalendar = Booking::with([
    'course:id,title',
    'topic:id,course_id,name',
    'teacher:id,name'
])
->where('student_id', $user->id) // 講師画面なら teacher_id に変更
->orderBy('date')->orderBy('time')
->get();

// Controller の map 部分を差し替え
$fcEvents = $bookingsForCalendar->map(function (Booking $b) {
    // JSTの壁時計での開始・終了をまず作る
$startTokyo = $b->startCarbon('Asia/Tokyo');  // 例: 2025-11-05 12:00:00 +09:00
$endTokyo   = $b->endCarbon('Asia/Tokyo');    // 例: 2025-11-05 12:50:00 +09:00

// 絶対時刻に直す（UTCへ）
// $startUtc = $startTokyo->clone()->setTimezone('UTC');
// $endUtc   = $endTokyo  ->clone()->setTimezone('UTC');

    $title = trim(($b->course->title ?? '') . ' ' . ($b->topic->name ?? 'Lesson'));

    return [
        'id'    => $b->id,
        'title' => $title !== '' ? $title : 'Lesson',

      'start' => $startTokyo->toIso8601String(),   // 12:00:00+09:00
      'end'   => $endTokyo->toIso8601String(),     // 12:50:00+09:00

        'extendedProps' => [
            'teacher'     => $b->teacher->name ?? null,
            'course_name' => $b->course->title ?? null,
            'topic_name'  => $b->topic->name ?? null,
            'course_id'   => $b->course_id,
            'topic_id'    => $b->topic_id,
        ],
    ];
})->values();

        return view('student.index', compact('courses', 'upNext', 'history', 'fcEvents'));
    }
}
?>