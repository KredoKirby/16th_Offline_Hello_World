@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド: コース一覧 --}}
        <div class="col-md-3 border-end bg-white" style="min-height:100vh;">
            <h3 class="fw-bold mb-3">Courses</h3>

            @foreach($courses as $c)
                <a href="{{ route('courses.show', $c->id) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center mb-3 p-2 border rounded shadow-sm 
                                {{ $course->id === $c->id ? 'bg-light border-primary' : '' }}">
                        <img src="{{ $c->image ?? 'https://via.placeholder.com/60x60' }}" 
                             alt="{{ $c->title }}" 
                             class="rounded me-2" style="width:60px;height:60px;object-fit:cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $c->title }}</h6>

                            @if(in_array($c->id, $enrolledCourseIds ?? []))
                                @php $rate = $c->completionRate(auth()->id()); @endphp
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $rate }}% Finish</small>
                            @else
                                <small class="text-muted">{{ $c->language ?? 'English' }}</small>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- 右サイド: 受講中 or 未受講 --}}
        <div class="col-md-9 ps-4">
            @if(in_array($course->id, $enrolledCourseIds ?? []))
                {{-- 受講中: 進捗UI --}}
                @include('courses.partials.enrolled', [
                    'course' => $course,
                    'sectionProgress' => $sectionProgress,
                    'coursePercent' => $coursePercent,
                    'completedLessonIds' => $completedLessonIds,
                ])
            @else
                {{-- 未受講: プレビューUI --}}
                @include('courses.partials.preview', [
                    'course' => $course,
                ])
            @endif
        </div>

    </div>
</div>
@endsection
