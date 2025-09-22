<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class Enrollmentcontroller extends Controller
{
    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request, $courseId)
{
    $course = Course::findOrFail($courseId);

    $course->enrollments()->firstOrCreate([
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('courses.show', $courseId)
        ->with('success', 'You have enrolled in this course!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
{
    $course->enrollments()->where('user_id', auth()->id())->delete();

    return redirect()->route('courses.index')
        ->with('success', 'You have unenrolled from this course.');
}

}
