<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request, Lesson $lesson)
    {
        $user = auth()->user();
        $isCompleted = (bool) $request->input('is_completed');

        if ($user->lessons()->where('lessons.id', $lesson->id)->exists()) {
            $user->lessons()->updateExistingPivot($lesson->id, [
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]);
        } else {
            $user->lessons()->attach($lesson->id, [
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]);
        }

        return back();
    }
}
