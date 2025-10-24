<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CourseInitApiController extends Controller
{
    public function show(Course $course)
    {
        $studentId = Auth::id();

        // 1) トピック一覧
        $topics = $course->topics()
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

        // 2) 「過去の予約の中で最新」から next_topic（無ければ最初のtopic）
        $suggested = $this->suggestedTopicId($studentId, $course->id, $topics);

        // 3) 対象コースを担当できる teacher の空きスロット（現在+1時間以降）
        $teacherIds = $course->teachers()->pluck('users.id');

        $slots = Booking::query()
            ->whereIn('teacher_id', $teacherIds)
            ->whereNull('student_id') // 空き枠のみ
            ->whereRaw('TIMESTAMP(`date`,`time`) >= ?', [Carbon::now()->addHour()->toDateTimeString()])
            ->with(['teacher:id,name'])
            ->orderBy('date')->orderBy('time')
            ->get(['id','teacher_id','date','time'])
            ->map(fn($b) => [
                'booking_id'   => $b->id,
                'date'         => $b->date,                  // 'YYYY-MM-DD'
                'time'         => substr($b->time, 0, 5),    // 'HH:MM'
                'teacher_id'   => $b->teacher_id,
                'teacher_name' => $b->teacher?->name ?? 'Teacher',
            ])
            ->values();

        return response()->json([
            'topics'    => $topics,     // [{id,name},...]
            'suggested' => $suggested,  // int|null
            'slots'     => $slots,      // [{booking_id,date,time,teacher_id,teacher_name},...]
        ]);
    }

    private function suggestedTopicId(int $studentId, int $courseId, $topics): ?int
    {
        if ($topics->isEmpty()) return null;

        $now = Carbon::now();

        // A) next_topicが入っている「過去の予約」の中で最新を優先
        $lastWithNext = Booking::where('student_id',$studentId)
            ->where('course_id',$courseId)
            ->whereRaw('TIMESTAMP(`date`,`time`) <= ?', [$now->toDateTimeString()])
            ->whereHas('report', fn($q)=>$q->whereNotNull('next_topic'))
            ->with('report:id,booking_id,next_topic')
            ->orderByDesc('date')->orderByDesc('time')
            ->first();

        if ($lastWithNext) {
            $next = (int) $lastWithNext->report->next_topic;
            $inCourse = Topic::where('id',$next)->where('course_id',$courseId)->exists();
            if ($inCourse) return $next;
        }

        // B) それが無ければ「最新の過去予約」のreportを見る
        $lastPast = \App\Models\Booking::where('student_id',$studentId)
            ->where('course_id',$courseId)
            ->whereRaw('TIMESTAMP(`date`,`time`) <= ?', [$now->toDateTimeString()])
            ->with('report:id,booking_id,next_topic')
            ->orderByDesc('date')->orderByDesc('time')
            ->first();

        if ($lastPast && optional($lastPast->report)->next_topic) {
            $next = (int) $lastPast->report->next_topic;
            $inCourse = Topic::where('id',$next)->where('course_id',$courseId)->exists();
            if ($inCourse) return $next;
        }

        // C) フォールバック：最初のトピック
        return $topics->first()->id;
    }
}