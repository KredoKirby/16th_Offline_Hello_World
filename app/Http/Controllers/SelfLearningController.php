<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson; 

class SelfLearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 受講中のコース
        $myCourses = $user->courses()->with('sections.lessons')->get();

        // 完了したコース数（100% のもの）
        $completedCourses = $myCourses->filter(function ($course) use ($user) {
            return $course->completionRate($user->id) === 100;
        })->count();

        // 学習時間
        $hoursLearned =0;
        //  \DB::table('lesson_user')
        //     ->where('user_id', $user->id)
        //     ->sum('duration'); 

        // おすすめコース（ここでは仮に最新の3件を表示）
        $recommendedCourses = Course::latest()->take(3)->get();

        return view('selflearning.index', compact(
            'myCourses',
            'completedCourses',
            'hoursLearned',
            'recommendedCourses'
        ));
    }

     // 詳細
    public function show($id)
    {
        $course = Course::with('sections')->findOrFail($id);
        return view('selflearning.show', compact('course'));
    }

    // レッスン再生ページ
   public function lessonVideo($courseId, $lessonId)
        {
            $course = Course::with('sections.lessons')->findOrFail($courseId);
            $lessons = $course->sections->flatMap->lessons->values(); // 全レッスンをフラットに
            $currentLesson = $lessons->firstWhere('id', $lessonId);

            $currentIndex = $lessons->search(fn($l) => $l->id === $currentLesson->id);
            $previousLesson = $lessons->get($currentIndex - 1);
            $nextLesson = $lessons->get($currentIndex + 1);

            return view('selflearning.lesson-video', [
                'course' => $course,
                'currentLesson' => $currentLesson,
                'previousLesson' => $previousLesson,
                'nextLesson' => $nextLesson,
            ]);
        }


        public function lessonText($courseId, $lessonId)
            {
                // コースを取得
                $course = Course::with('sections.lessons')->findOrFail($courseId);

                // 該当レッスンを取得
                $currentLesson = $course->sections
                    ->flatMap->lessons   
                    ->firstWhere('id', $lessonId);

                if (!$currentLesson) {
                    abort(404, 'Lesson not found');
                }

                return view('selflearning.lesson-text', compact('course', 'currentLesson'));
            }

            public function lessonDone($courseId, $lessonId) {
                $lesson = Lesson::findOrFail($lessonId);

                auth()->user()->completedLessons()->syncWithoutDetaching([
                    $lesson->id => ['completed_at' => now()]
                ]);

                return redirect()->route('selflearning.lessonVideo', [$courseId, $lessonId])
                                ->with('success', 'Lesson marked as completed!');
}

            

}
