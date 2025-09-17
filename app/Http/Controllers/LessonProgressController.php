<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonProgressController extends Controller
{
    // LessonProgressController.php
public function update(Request $request, Lesson $lesson)
{
    $user = auth()->user();

    $lesson->progress()->updateOrCreate(
        ['user_id' => $user->id],
        ['completed' => $request->completed]
    );

    return response()->json(['status' => 'ok']);
}

    public function toggle(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        if ($user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
            // すでに完了 → 削除
            $user->completedLessons()->detach($lesson->id);
            $status = 'unchecked';
        } else {
            // 未完了 → 追加
            $user->completedLessons()->attach($lesson->id);
            $status = 'checked';
        }

        return response()->json([
            'status' => $status,
            'lesson_id' => $lesson->id
        ]);
    }
}

