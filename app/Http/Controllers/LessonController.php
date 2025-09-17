<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Progress;

class LessonController extends Controller
{
   public function updateProgress(Request $request, Lesson $lesson)
{
    $user = auth()->user();

    $lesson->progress()->updateOrCreate(
        ['user_id' => $user->id],
        ['completed' => $request->completed]
    );

    return response()->json(['status' => 'ok']);
}

}
