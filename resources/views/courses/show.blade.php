@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド --}}
        <div class="col-md-3 border-end bg-white" style="min-height:100vh;">
            <h3 class="fw-bold mb-3">
                <a href="{{ route('courses.index') }}" class="text-decoration-none text-dark">Courses</a>
            </h3>

            @include('courses.partials.left-sidebar', ['courses'=>$courses, 'selectedCourse'=>$course])
        </div>

        {{-- 右サイド --}}
        <div class="col-md-9 ps-4">
            @if(in_array($course->id, $enrolledCourseIds ?? []))
                @include('courses.partials.enrolled', [
                    'course' => $course,
                    'sectionProgress' => $sectionProgress,
                    'coursePercent' => $coursePercent,
                    'completedLessonIds' => $completedLessonIds,
                ])
            @else
                @include('courses.partials.preview', ['course' => $course])
            @endif
        </div>

    </div>
</div>
@endsection
