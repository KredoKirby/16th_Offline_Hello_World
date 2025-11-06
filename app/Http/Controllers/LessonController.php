<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function toggleStatus(Lesson $lesson)
    {
        $lesson->status = !$lesson->status; // 1→0  /  0→1 切り替え
        $lesson->save();

        return back()->with('success', 'Lesson status updated!');
    }

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
            'id' => $lesson->id,
            'title' => $lesson->title,
            'content' => $lesson->content ?? 'No content yet',
        ]);
    }

    public function toggle(Lesson $lesson)
    {
        // status / is_active どちらでも対応（存在する方を更新）
        if ($lesson->isFillable('is_active') || array_key_exists('is_active', $lesson->getAttributes())) {
            $lesson->is_active = !(bool) $lesson->is_active;
        } else {
            // status が bool/0/1/'active'想定
            $current = $lesson->status;
            $isActive = is_bool($current) ? $current
                : (strtolower((string) $current) === 'active' || (int) $current === 1);
            $lesson->status = $isActive ? 0 : 1;
        }

        $lesson->save();

        return back()->with('success', 'Lesson status updated.');
    }
}
