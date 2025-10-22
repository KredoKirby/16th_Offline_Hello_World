<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Topic;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1) 入力バリデーション
        $validated = $request->validate([
            'course_id' => ['required','integer','exists:courses,id'],
            'topic_id'  => ['required','integer','exists:topics,id'],
            'date'      => ['required','date','after_or_equal:today'],
            'time'      => ['required','date_format:H:i'],
        ]);

        $studentId = Auth::id();
        $courseId  = (int)$validated['course_id'];
        $topicId   = (int)$validated['topic_id'];
        $date      = $validated['date'];         // 'YYYY-MM-DD'
        $timeHhmm  = $validated['time'];         // 'HH:MM'
        $time      = $timeHhmm.':00';            // DBのTIME型に合わせて 'HH:MM:SS'

        // 2) topic が course に属するかを確認（安全）
        $topicBelongs = Topic::where('id',$topicId)->where('course_id',$courseId)->exists();
        if (!$topicBelongs) {
            return back()->withErrors(['topic_id' => 'Selected topic does not belong to the chosen course.'])->withInput();
        }

        // 3) もし今日を選んでいるなら「現在+1時間」以降のみ許可（任意・推奨）
        $requested = Carbon::createFromFormat('Y-m-d H:i:s', "$date $time");
        if (Carbon::today()->isSameDay($requested) && $requested->lt(now()->addHour())) {
            return back()->withErrors(['time' => 'Please pick a time at least 1 hour from now.'])->withInput();
        }

        // 4) このコースを担当できる先生IDを取得（teacher_course ピボット）
        $teacherIds = Course::findOrFail($courseId)->teachers()->pluck('users.id');

        if ($teacherIds->isEmpty()) {
            return back()->withErrors(['course_id' => 'No teacher is assigned to this course.'])->withInput();
        }

        // 5) その日時の「空き枠 (= student_id が NULL)」を 1 件だけ確保して更新
        //    競合防止のためトランザクション + 行ロック（悲観ロック）
        try {
            DB::transaction(function () use ($studentId, $courseId, $topicId, $date, $time, $teacherIds) {
                // 先生は「このコースを教えられる先生たち」の中から
                // 指定の date/time で空いている枠を取る
                $booking = Booking::whereIn('teacher_id', $teacherIds)
                    ->whereDate('date', $date)
                    ->where('time', $time)
                    ->whereNull('student_id')
                    ->orderBy('id')             // 同時間帯に複数空きがあれば先頭を確保
                    ->lockForUpdate()           // ← これがポイント：同時予約の競合を防止
                    ->first();

                if (!$booking) {
                    abort(422, 'No open slot found for the chosen date/time.');
                }

                // NULL の部分を埋める（今回受けるトピック/コース/学生をセット）
                $booking->update([
                    'student_id' => $studentId,
                    'course_id'  => $courseId,
                    'topic_id'   => $topicId,
                    // updated_at は Eloquent の timestamps で自動更新
                ]);
            });
        } catch (\Throwable $e) {
            return back()->withErrors(['date' => $e->getMessage()])->withInput();
        }

        return back()->with('status', 'Booked successfully!');
    }
}
