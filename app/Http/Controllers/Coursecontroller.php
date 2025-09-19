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

        // ログイン中ユーザーのデータを取得
        $user = auth()->user();

        $completedLessonIds = $user
            ? $user->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray()
            : [];

        // ユーザーが受講中のコースID配列
        $enrolledCourseIds = $user
            ? $user->courses()->pluck('courses.id')->toArray()
            : [];

        // 各コースの進捗(%)を作る
        $courseProgress = [];
        foreach ($courses as $course) {
            $lessonIds = $course->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();

            $total = count($lessonIds);
            $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
            $courseProgress[$course->id] = $total ? round($completed / $total * 100) : 0;
        }

        return view('courses.index', compact('courses', 'courseProgress', 'enrolledCourseIds'));
    }

    public function show($id)
    {
        $course = Course::with('sections.lessons')->findOrFail($id);

        $user = auth()->user();

        $completedLessonIds = $user
            ? $user->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray()
            : [];

        // 受講中コースID
        $enrolledCourseIds = $user
            ? $user->courses()->pluck('courses.id')->toArray()
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

         $courses = Course::all();

        return view('courses.show', compact(
            'course',
            'sectionProgress',
            'coursePercent',
            'completedLessonIds',
            'enrolledCourseIds',
             'courses'
        ));
    }

    public function enroll(Course $course)
    {
        $user = auth()->user();

        // すでに受講していないか確認
        if (!$user->courses->contains($course->id)) {
            $user->courses()->attach($course->id);
        }

        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'You have enrolled in the course!');
    }
}
