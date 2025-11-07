<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseTopicController extends Controller
{
    // 個別トグル
    public function toggle(Request $request, Course $course)
    {
        DB::transaction(function () use ($course) {
            $course->status = (bool) $course->status ? 0 : 1;
            $course->save();
            $course->topics()->update(['status' => $course->status]);
        });

        // ★ここを変更：open=<id> と #heading-<id> を付けて返す
        return redirect()->to(
            route('admin.courses.index', ['open' => $course->id]) . '#heading-' . $course->id
        )->with('success', $course->status ? 'Course & all topics activated.' : 'Course & all topics deactivated.');
    }

    // もし toggleAll を使うなら、同じ要領で open を付ける
    public function toggleAll(Request $request)
    {
        $to = strtolower($request->input('to', 'active'));
        $new = $to === 'active' ? 1 : 0;

        DB::transaction(function () use ($new) {
            Course::query()->update(['status' => $new]);
            Topic::query()->update(['status' => $new]);
        });

        $openId = $request->input('open'); // 送っていれば使う

        return redirect()->to(
            $openId
            ? route('admin.courses.index', ['open' => $openId]) . '#heading-' . $openId
            : route('admin.courses.index')
        )->with('success', $new ? 'All courses & topics activated.' : 'All courses & topics deactivated.');
    }


    // public function toggle(Request $request, Topic $topic)
    // {
    //     // 0/1 のトグル or 明示指示（to=active/deactive）
    //     $to = strtolower($request->input('to', ''));
    //     $current = (int) ($topic->status ?? 0);

    //     if ($to === 'active') {
    //         $new = 1;
    //     } elseif ($to === 'deactive' || $to === 'inactive') {
    //         $new = 0;
    //     } else {
    //         $new = $current ? 0 : 1;
    //     }

    //     $topic->status = $new;
    //     $topic->save();

    //     return back()->with('success', $new ? 'Topic activated.' : 'Topic deactivated.');
    // }
}
