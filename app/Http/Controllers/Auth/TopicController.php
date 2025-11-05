<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class TopicController extends Controller
{
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
        ]);

        $topic = new Topic();
        $topic->course_id = $courseId;
        $topic->title = $request->title;
        $topic->description = $request->description;
        $topic->video_url = $request->video_url;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('topics', 'public');
            $topic->image = $path;
        }

        $topic->save();

        return back()->with('success', 'Topic added successfully!');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->image && Storage::disk('public')->exists($topic->image)) {
            Storage::disk('public')->delete($topic->image);
        }
        $topic->delete();
        return back()->with('success', 'Topic deleted successfully!');
    }
}
