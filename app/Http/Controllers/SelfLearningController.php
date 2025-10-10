<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfLearningController extends Controller
{
    // 学習一覧ページ
    public function index(Request $request)
    {
        // 1. Get the search keyword from the form
        $search = $request->input('search');
        
        $user = auth()->user();

        // 2. Start building the query for the user's enrolled courses
        $coursesQuery = $user->courses()->getQuery();

        // 3. If a search keyword exists, apply a filter
        if ($search) {
            $coursesQuery->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // 4. Execute the query to get the (now possibly filtered) courses
        $myCourses = $coursesQuery->get();

        // 🔹 完了したコース数
        $completedCourses = $myCourses->filter(function ($course) use ($user) {
            return $course->completionRate($user->id) >= 100;
        })->count();

        // 🔹 総学習時間（秒単位）
        $hoursLearned = $user->completedLessons()->sum('lesson_user.study_time');

        // 🔹 おすすめコース（自分が未受講のものからランダムに5件）
        $recommendedCourses = \App\Models\Course::whereNotIn(
            'id',
            $user->courses->pluck('id')
        )->inRandomOrder()->take(5)->get();

        // 🔹 ビューへ渡す
        return view('selflearning.index', compact(
            'myCourses',
            'completedCourses',
            'hoursLearned',
            'recommendedCourses'
        ));
    }

    // 🔹 秒を時間表記に変換
    private function formatTime($seconds)
    {
        if ($seconds < 60) {
            return $seconds . '秒';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . '分' . ($seconds % 60) . '秒';
        } else {
            return floor($seconds / 3600) . '時間' . floor(($seconds % 3600) / 60) . '分' . ($seconds % 60) . '秒';
        }
    }

    // 🔹 コース詳細
    public function show($id)
    {
        $course = Course::with('lessons')->findOrFail($id);
        return view('selflearning.show', compact('course'));
    }

    // 🔹 レッスン動画ページ
    public function lessonVideo($courseId, $lessonId)
    {
        $course = Course::with('lessons')->findOrFail($courseId);
        $lessons = $course->lessons;

        $currentLesson = $lessons->firstWhere('id', $lessonId);
        if (!$currentLesson) abort(404, 'Lesson not found');

        $currentIndex = $lessons->search(fn($l) => $l->id === $currentLesson->id);
        $previousLesson = $lessons->get($currentIndex - 1);
        $nextLesson = $lessons->get($currentIndex + 1);

        // 総学習時間（表示用）
        $totalSeconds = Auth::user()->completedLessons()->sum('lesson_user.study_time');
        $hoursLearned = $this->formatTime($totalSeconds);

        return view('selflearning.lesson-video', compact(
            'course', 'currentLesson', 'previousLesson', 'nextLesson', 'hoursLearned'
        ));
    }

    // 🔹 レッスンテキストページ（section_id → course_idに修正）
    public function lessonText($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);

        $lessons = Lesson::where('course_id', $course->id)
            ->orderBy('id')
            ->get();

        $currentLesson = $lessons->firstWhere('id', $lessonId);
        if (!$currentLesson) abort(404, 'Lesson not found');

        $currentIndex = $lessons->search(fn($lesson) => $lesson->id == $lessonId);
        $totalLessons = $lessons->count();

        return view('selflearning.lesson-text', compact(
            'course',
            'currentLesson',
            'currentIndex',
            'totalLessons',
            'lessons'
        ));
    }

    // 🔹 レッスン完了処理
    public function lessonDone($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        auth()->user()->completedLessons()->syncWithoutDetaching([
            $lesson->id => ['completed_at' => now(), 'is_completed' => true]
        ]);

        return redirect()->route('selflearning.lessonVideo', [$courseId, $lessonId])
            ->with('success', 'Lesson marked as completed!');
    }

    // 🔹 Lesson 完了トグル + 秒単位記録
    public function toggleLesson($courseId, $lessonId)
    {
        $user = auth()->user();
        $lesson = Lesson::findOrFail($lessonId);
        $studyTime = request()->input('study_time', 30); // デフォルト30秒

        if ($user->completedLessons->contains($lessonId)) {
            $user->completedLessons()->detach($lessonId);
            $status = 'unchecked';
        } else {
            $user->completedLessons()->attach($lessonId, [
                'is_completed' => true,
                'completed_at' => now(),
                'study_time' => $studyTime,
            ]);
            $status = 'checked';
        }

        $totalSeconds = $user->completedLessons()->sum('lesson_user.study_time');
        $hoursLearned = $this->formatTime($totalSeconds);

        return response()->json([
            'status' => $status,
            'hours_learned' => $hoursLearned,
        ]);
    }

    // 🔹 study_time更新処理（秒単位で自動加算）
    public function updateStudyTime(Request $request)
    {
        $user = Auth::user();
        $lessonId = (int) $request->input('lesson_id');
        $seconds  = (int) $request->input('seconds', 0);

        if (!$lessonId || $seconds <= 0) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }

        // check if pivot row exists
        $existing = $user->completedLessons()->where('lessons.id', $lessonId)->first();

        if ($existing) {
            $current = (int) ($existing->pivot->study_time ?? 0);
            $new = $current + $seconds;
            $user->completedLessons()->updateExistingPivot($lessonId, [
                'study_time' => $new,
                'updated_at' => now(),
            ]);
        } else {
            // attach new pivot row (未完了フラグで作る)
            $user->completedLessons()->attach($lessonId, [
                'is_completed' => false,
                'study_time' => $seconds,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 全体の合計（秒）
        $totalSeconds = (int) $user->completedLessons()->sum('lesson_user.study_time');

        // フォーマット文字列（例: 1h 2m 3s または 10s）
        $formatted = $this->formatTime($totalSeconds);

        return response()->json([
            'total_study_time' => $totalSeconds,
            'formatted_time' => $formatted,
            'message' => '+' . $seconds . ' sec recorded!',
        ]);
    }
}