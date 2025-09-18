<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // すべてのコースを sections->lessons を eager load
        $courses = Course::with('sections.lessons')->get();

        // ユーザーがログインしていれば完了したレッスンID配列を取得
        $completedLessonIds = auth()->check()
            ? auth()->user()->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray()
            : [];

        // 各コースの進捗(%)を作る
        $courseProgress = [];
        foreach ($courses as $course) {
            $lessonIds = $course->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();

            $total = count($lessonIds);
            $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
            $courseProgress[$course->id] = $total ? round($completed / $total * 100) : 0;
        }

        return view('courses.index', compact('courses', 'courseProgress'));
    }

    public function show($id)
    {
        $course = Course::with('sections.lessons')->findOrFail($id);

        $completedLessonIds = auth()->check()
            ? auth()->user()->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray()
            : [];

        $sectionProgress = [];
        $totalCourseLessons = 0;
        $completedCourseLessons = 0;

        foreach ($course->sections as $section) {
            $lessonIds = $section->lessons->pluck('id')->toArray();
            $total = count($lessonIds);
            $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
            $percent = $total ? round($completed / $total * 100) : 0;

            $sectionProgress[$section->id] = [
                'percent'   => $percent,
                'total'     => $total,
                'completed' => $completed,
            ];

            $totalCourseLessons += $total;
            $completedCourseLessons += $completed;
        }

        $coursePercent = $totalCourseLessons ? round($completedCourseLessons / $totalCourseLessons * 100) : 0;

        return view('courses.show', compact(
            'course',
            'sectionProgress',
            'coursePercent',
            'completedLessonIds'
        ));
    }

    public function enroll(Course $course)
{
    $user = auth()->user();

    // すでに受講していないか確認
    if (!$user->courses->contains($course->id)) {
        $user->courses()->attach($course->id);
    }

    return redirect()->route('courses.index', ['course_id' => $course->id])
                     ->with('success', 'You have enrolled in the course!');
}

}
