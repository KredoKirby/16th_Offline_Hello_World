<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Report;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TopicApiController extends Controller
{
    public function byCourse(Course $course)
    {
        $studentId = Auth::id();

        // そのコースの全トピックを取得
        $topics = $course->topics()->select('id','name')->orderBy('id')->get();

        // 「次のTopic」= 最新Booking→Report→next_topicを確認
        $suggested = $this->getNextTopicFromReports($studentId, $course->id, $topics);

        return response()->json([
            'topics'    => $topics,
            'suggested' => $suggested,
        ]);
    }

        /**
     * 「過去の booking かつ最新のもの」に紐づく Report から next_topic を返す。
     * - 過去判定: date < 今日 もしくは (date = 今日 かつ time <= 現在時刻)
     * - 最新判定: date desc, time desc, id desc の優先順位で1件
     * - Report が無い / next_topic が null のときはコース最初の topic を返す
     */
    private function getNextTopicFromReports(int $studentId, int $courseId, $topics): ?int
    {
        if ($topics->isEmpty()) return null;

        // タイムゾーンに注意（アプリの timezone を使用）
        $now      = Carbon::now();                 // 例: Asia/Tokyo
        $today    = $now->toDateString();          // 'YYYY-MM-DD'
        $nowTime  = $now->format('H:i:s');         // 'HH:MM:SS'（DB の TIME と比較）

        // 1) 「過去の booking」の中から最新を 1 件取得
        $lastPastBooking = Booking::where('student_id', $studentId)
            ->where('course_id',  $courseId)
            ->where(function ($q) use ($today, $nowTime) {
                $q->where('date', '<', $today)
                ->orWhere(function ($qq) use ($today, $nowTime) {
                    $qq->where('date', $today)
                        ->where('time', '<=', $nowTime);
                });
            })
            ->orderByDesc('date')
            ->orderByDesc('time')
            // ->orderByDesc('id')
            ->first();

        // 過去に受講が無い → コース最初の topic をデフォルト返却
        if (!$lastPastBooking) {
            return $topics->first()->id;
        }

        // 2) 紐づくレポートを取得（1対1想定）
        //   ※「status は attended/absent/leave in the middle のいずれか」という要件を満たす前提で、
        //     追加の whereIn は今回は必須ではありません（必要ならコメントアウト解除）。
        $report = $lastPastBooking->report;

        // $report = Report::where('booking_id', $lastPastBooking->id)
        //     ->whereIn('status', ['attended','absent','leave in the middle'])
        //     ->first();

        // 3) next_topic が入っていればそれを返す。無ければ最初の topic。
        if ($report && $report->next_topic) {
            return (int) $report->next_topic;
        }

        return $topics->first()->id;
    }
}