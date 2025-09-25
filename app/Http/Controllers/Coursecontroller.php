<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Course::with('sections.lessons');

        // 検索
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 言語フィルタ
        if ($request->filled('lang')) {
            $query->where('language', $request->lang);
        }

        // ステータスフィルタ（active / completed）
        $courses = $query->get();

        if ($request->status && $user) {
            $completedLessonIds = $user->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray();

            if ($request->status === 'active') {
                $courses = $courses->filter(function ($c) use ($completedLessonIds) {
                    $lessonIds = $c->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();
                    $total = count($lessonIds);
                    $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
                    return $total > 0 && $completed < $total;
                });
            } elseif ($request->status === 'completed') {
                $courses = $courses->filter(function ($c) use ($completedLessonIds) {
                    $lessonIds = $c->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();
                    $total = count($lessonIds);
                    $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
                    return $total > 0 && $completed === $total;
                });
            }
        }

        // ページネーション（コレクションを paginate に変換）
        $page = $request->get('page', 1);
        $perPage = 10;
        $courses = new \Illuminate\Pagination\LengthAwarePaginator(
            $courses->forPage($page, $perPage),
            $courses->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ログインユーザーが enroll 済みの course_id
        $enrolledCourseIds = $user
            ? $user->courses()->pluck('courses.id')->toArray()
            : [];

        return view('courses.index', compact('courses', 'enrolledCourseIds'));
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $course = Course::with('sections.lessons')->findOrFail($id);

        $completedLessonIds = $user
            ? $user->lessons()->wherePivot('is_completed', true)->pluck('lessons.id')->toArray()
            : [];

        $enrolledCourseIds = $user
            ? $user->courses()->pluck('courses.id')->toArray()
            : [];

        // 左サイド一覧
        $query = Course::with('sections.lessons');
        if ($request->filled('lang')) {
            $query->where('language', $request->lang);
        }
        $courses = $query->get();

        if ($request->status && $user) {
            if ($request->status === 'active') {
                $courses = $courses->filter(function ($c) use ($completedLessonIds) {
                    $lessonIds = $c->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();
                    $total = count($lessonIds);
                    $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
                    return $total > 0 && $completed < $total;
                });
            } elseif ($request->status === 'completed') {
                $courses = $courses->filter(function ($c) use ($completedLessonIds) {
                    $lessonIds = $c->sections->flatMap(fn($s) => $s->lessons)->pluck('id')->toArray();
                    $total = count($lessonIds);
                    $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
                    return $total > 0 && $completed === $total;
                });
            }
        }

        // 進捗計算
        $sectionProgress = [];
        $totalCourseLessons = 0;
        $completedCourseLessons = 0;
        foreach ($course->sections as $section) {
            $lessonIds = $section->lessons->pluck('id')->toArray();
            $total = count($lessonIds);
            $completed = $total ? count(array_intersect($lessonIds, $completedLessonIds)) : 0;
            $sectionProgress[$section->id] = [
                'percent' => $total ? round($completed / $total * 100) : 0,
                'total' => $total,
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
            'completedLessonIds',
            'enrolledCourseIds',
            'courses'
        ));
    }

    public function enroll(Course $course)
    {
        $user = auth()->user();
        if (!$user->courses->contains($course->id)) {
            $user->courses()->attach($course->id);
        }
        return redirect()->route('courses.show', $course->id)
                         ->with('success', 'You have enrolled in the course!');
    }

   public function unenroll($id)
{
    $course = Course::findOrFail($id);

    // enroll 解除処理
    auth()->user()->courses()->detach($course->id);

    return redirect()->route('courses.index')
        ->with('success', 'You have successfully unenrolled from the course.');
}


}
