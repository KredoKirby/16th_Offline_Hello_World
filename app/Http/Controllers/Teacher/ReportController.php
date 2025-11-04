<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Topic;
use App\Models\Report;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Report の status 候補（ビューの <select> と合わせる）
    public const ALLOWED_STATUSES = [
        'attended',
        'absent',
        'canceled by teacher',
        'others',
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
        'status'         => ['required', 'nullable','string','in:' . implode(',', self::ALLOWED_STATUSES)],
        'feedback'       => ['required', 'nullable','string','max:5000'],
        'next_topic'  => ['required', 'nullable','integer','exists:topics,id'], // ★ IDで受ける
    ]);

    $report = Report::firstOrNew(['booking_id' => $booking->id]);
    if (array_key_exists('status', $data))        $report->status = $data['status'];
    if (array_key_exists('feedback', $data))      $report->feedback = $data['feedback'];
    if (array_key_exists('next_topic', $data)) $report->next_topic = $data['next_topic'];
    $report->save();

    return response()->json([
        'ok' => true,
        'report' => [
            'id'            => $report->id,
            'status'        => $report->status,
            'feedback'      => $report->feedback,
            'next_topic' => $report->next_topic,
        ],
    ]);
}
}