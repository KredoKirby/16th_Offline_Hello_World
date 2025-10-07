<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;

class SelfLearningController extends Controller
{
  public function index(Request $request)
    {
        $user = Auth::user();
        $query = $request->input('search');

        // 受講中のコース
        $myCourses = $user->courses()->with('sections.lessons');

        // 検索条件がある場合
        if ($query) {
            $myCourses->where('title', 'like', '%' . $query . '%');
        }

        $myCourses = $myCourses->get();

        // ★ status 自動更新
        foreach ($myCourses as $course) {
            $completion = $course->completionRate($user->id);

            if ($completion >= 100 && $course->status !== 'completed') {
                $course->update(['status' => 'completed']);
                $course->status = 'completed'; // コレもセットしてBladeで反映
            } elseif ($completion < 100 && $course->status !== 'active') {
                $course->update(['status' => 'active']);
                $course->status = 'active';
            }
        }

        // 完了したコース数
        $completedCourses = $myCourses->filter(function ($course) use ($user) {
            return $course->completionRate($user->id) === 100;
        })->count();

        // 学習時間（今は仮で0）
        $hoursLearned = 0;

        // おすすめコース（最新3件）
        $recommendedCourses = Course::latest()->take(3)->get();

        return view('selflearning.index', compact(
            'myCourses',
            'completedCourses',
            'hoursLearned',
            'recommendedCourses'
        ));
    }



    // コース詳細
    public function show($id)
    {
        $course = Course::with('sections')->findOrFail($id);
        return view('selflearning.show', compact('course'));
    }

    // レッスン動画ページ
    public function lessonVideo($courseId, $lessonId)
    {
        $course = Course::with('sections.lessons')->findOrFail($courseId);
        $lessons = $course->sections->flatMap->lessons->values();

        $currentLesson = $lessons->firstWhere('id', $lessonId);
        if (!$currentLesson) {
            abort(404, 'Lesson not found');
        }

        $currentIndex = $lessons->search(fn($l) => $l->id === $currentLesson->id);
        $previousLesson = $lessons->get($currentIndex - 1);
        $nextLesson = $lessons->get($currentIndex + 1);

        return view('selflearning.lesson-video', compact(
            'course', 'currentLesson', 'previousLesson', 'nextLesson'
        ));
    }

    // レッスンテキストページ
   public function lessonText($courseId, $lessonId)
{
    $course = Course::findOrFail($courseId);

    // コースに含まれるすべてのレッスンを取得
    $lessons = Lesson::whereIn('section_id', $course->sections->pluck('id'))
        ->orderBy('id')
        ->get();

    // 現在のレッスン
    $currentLesson = $lessons->firstWhere('id', $lessonId);

    // インデックス計算
    $currentIndex = $lessons->search(function ($lesson) use ($lessonId) {
        return $lesson->id == $lessonId;
    });

    $totalLessons = $lessons->count();

    return view('selflearning.lesson-text', compact(
        'course',
        'currentLesson',
        'currentIndex',
        'totalLessons',
        'lessons'
    ));
}


    // レッスン完了処理
    public function lessonDone($courseId, $lessonId) {
        $lesson = Lesson::findOrFail($lessonId);

        auth()->user()->completedLessons()->syncWithoutDetaching([
            $lesson->id => ['completed_at' => now()]
        ]);

        return redirect()->route('selflearning.lessonVideo', [$courseId, $lessonId])
                        ->with('success', 'Lesson marked as completed!');
    }

   

        // Lesson 完了トグル
       public function toggleLesson($courseId, $lessonId)
        {
            $user = auth()->user();
            $lesson = Lesson::findOrFail($lessonId);

            // 既に完了していれば解除、未完了なら追加
            if ($user->completedLessons->contains($lessonId)) {
                $user->completedLessons()->detach($lessonId);
                $status = 'unchecked';
            } else {
                $user->completedLessons()->attach($lessonId);
                $status = 'checked';
            }

            return response()->json(['status' => $status]);
        }




    


}
