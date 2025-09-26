<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function updateProgress(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        // pivot を更新（受講記録がなければ attach）
        if ($user->lessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->lessons()->updateExistingPivot($lesson->id, [
                'is_completed' => $request->completed ? 1 : 0,
                'completed_at' => $request->completed ? now() : null,
            ]);
        } else {
            $user->lessons()->attach($lesson->id, [
                'is_completed' => $request->completed ? 1 : 0,
                'completed_at' => $request->completed ? now() : null,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function show(Lesson $lesson)
    {
        return response()->json([
            'id'      => $lesson->id,
            'title'   => $lesson->title,
            'content' => $lesson->content ?? 'No content yet',
        ]);
    }

    public function toggle(Request $request, $courseId, Lesson $lesson)
{
    $user = $request->user();

    $existing = $user->lessons()
        ->where('lesson_id', $lesson->id)
        ->wherePivot('is_completed', true)
        ->exists();

    if ($existing) {
        $user->lessons()->updateExistingPivot($lesson->id, [
            'is_completed' => false,
            'completed_at' => null,
        ]);
    } else {
        $user->lessons()->syncWithoutDetaching([
            $lesson->id => ['is_completed' => true, 'completed_at' => now()]
        ]);
    }

    return back();
}

}
