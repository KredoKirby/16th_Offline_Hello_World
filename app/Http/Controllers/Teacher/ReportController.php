<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Topic;
use App\Models\Report;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Report の status 候補（ビューの <select> と合わせる）
    public const ALLOWED_STATUSES = [
        'Attended',
        'Absent',
        'Canceled by teacher',
        'Others',
    ];

    /**
     * GET /teachers/reports/{booking}
     * Report モーダルの表示用データを返す（JSON）
     */
   public function show(Booking $booking)
{
    if ($booking->teacher_id !== Auth::id()) abort(403);

    $booking->load(['student:id,name,email', 'course:id,title', 'topic:id,name', 'report']);

    $date = $booking->date instanceof \Carbon\Carbon
        ? $booking->date->format('Y-m-d')
        : (string) $booking->date;

    $time = $booking->time instanceof \Carbon\Carbon
        ? $booking->time->format('H:i:s')
        : (string) $booking->time;
    if (preg_match('/^\d{2}:\d{2}$/', $time)) { $time .= ':00'; }

    $end = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "{$date} {$time}")
            ->addMinutes($booking->duration_minutes ?? 50)
            ->format('Y-m-d H:i');

    // ★ 予約のコースに紐づくトピックのみ取得（id昇順）
    $topics = \App\Models\Topic::query()
        ->when($booking->course_id, fn($q) => $q->where('course_id', $booking->course_id))
        ->orderBy('id')
        ->get(['id','name'])
        ->map(fn($t) => ['id' => $t->id, 'name' => $t->name ?? ''])
        ->values();

    // ★ 既定選択：report.next_topic があればそれ、無ければ予約の topic_id
    $preferredTopicId = optional($booking->report)->next_topic ?? $booking->topic_id;

    return response()->json([
        'booking' => [
            'id'    => $booking->id,
            'date'  => $date,
            'start' => substr($time, 0, 5),
            'end'   => \Carbon\Carbon::createFromFormat('Y-m-d H:i', $end)->format('H:i'),
        ],
        'student' => $booking->student ? [
            'id' => $booking->student->id,
            'name' => $booking->student->name,
            'email' => $booking->student->email,
        ] : null,
        'course' => $booking->course ? [
            'id' => $booking->course->id,
            'title' => $booking->course->title,
        ] : null,
        'topic' => $booking->topic ? [
            'id' => $booking->topic->id,
            'name' => $booking->topic->name,
        ] : null,
        'report' => $booking->report ? [
            'id'            => $booking->report->id,
            'status'        => $booking->report->status,
            'feedback'      => $booking->report->feedback,
            'next_topic' => $booking->report->next_topic, // ★ ここはID
        ] : [
            'id'            => null,
            'status'        => null,
            'feedback'      => null,
            'next_topic' => null,
        ],
        'topics' => $topics,
        'preferred_topic_id' => $preferredTopicId, // ★ JS側がそのまま初期選択できるように
    ]);
}

    /**
     * PATCH /teachers/reports/{booking}
     * Report の status / feedback を保存（Upsert）
     */
   public function update(Request $request, Booking $booking)
{
    if ($booking->teacher_id !== Auth::id()) abort(403);

    $data = $request->validate([
        'status'     => ['sometimes','nullable','string','in:' . implode(',', self::ALLOWED_STATUSES)],
        'feedback'   => ['sometimes','nullable','string','max:5000'],
        'next_topic' => ['sometimes','nullable','integer','exists:topics,id'],
    ]);

    $result = DB::transaction(function () use ($booking, $data) {

        // 1) まず「現在のレポート」を必ず保存（どの場合でも next_topic はここに入れる）
        $report = Report::firstOrNew(['booking_id' => $booking->id]);
        if (array_key_exists('status', $data))   $report->status   = $data['status'];
        if (array_key_exists('feedback', $data)) $report->feedback = $data['feedback'];
        if (array_key_exists('next_topic', $data)) $report->next_topic = $data['next_topic'];
        $report->save();

        $nextTopicId = $data['next_topic'] ?? null;
        if (!$nextTopicId) {
            return [
                'updated_report' => $report->only(['id','booking_id','status','feedback','next_topic']),
                'touched'        => null,
                'note'           => 'next_topic not provided; only current report saved.',
            ];
        }

        // 2) 同一生徒×同一コースの「現在より未来」の予約一覧（近い順）
        $studentId = $booking->student_id;
        $courseId  = $booking->course_id;
        $curDate   = $booking->date;
        $curTime   = $booking->time;

        $future = Booking::query()
            ->where('student_id', $studentId)
            ->where('course_id',  $courseId)
            ->where(function ($q) use ($curDate, $curTime) {
                $q->where('date', '>', $curDate)
                  ->orWhere(function ($q) use ($curDate, $curTime) {
                      $q->where('date', $curDate)->where('time', '>', $curTime);
                  });
            })
            ->with('report')
            ->orderBy('date')->orderBy('time')
            ->get();

        $norm = fn($s) => strtolower(trim((string)$s));
        $isNonCanceled = function ($rep) use ($norm) {
            return $rep && $norm($rep->status) !== 'canceled by teacher';
        };

        // 3) ①② いずれでも「reportあり × 非キャンセル」が将来に存在 → 何もしない
        $hasFutureNonCanceledWithReport = $future->first(
            fn($b) => $isNonCanceled($b->report)
        ) !== null;

        if ($hasFutureNonCanceledWithReport) {
            return [
                'updated_report' => $report->only(['id','booking_id','status','feedback','next_topic']),
                'touched'        => null,
                'note'           => 'future non-canceled booking with report exists; no propagation as requested.',
            ];
        }

        // 4) 上が無い場合だけ、最初の「reportなし」予約に topic をセット（任意の最小反映）
        $firstNoReport = $future->first(fn($b) => !$b->report);
        if ($firstNoReport) {
            $firstNoReport->topic_id = $nextTopicId;
            $firstNoReport->save();

            return [
                'updated_report' => $report->only(['id','booking_id','status','feedback','next_topic']),
                'touched'        => [
                    'booking_id' => $firstNoReport->id,
                    'action'     => 'set booking.topic_id because no future non-canceled-with-report exists',
                ],
            ];
        }

        // 5) それも無ければ何も変更しない
        return [
            'updated_report' => $report->only(['id','booking_id','status','feedback','next_topic']),
            'touched'        => null,
            'note'           => 'no future target to propagate; nothing else to do.',
        ];
    });

    return response()->json(['ok' => true, 'result' => $result]);
}
}