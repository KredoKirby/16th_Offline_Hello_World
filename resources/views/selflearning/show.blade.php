@extends('layouts.app')

@section('content')
<div class="container">

    {{-- 戻るボタン --}}
    <div class="mb-3">
        <a href="{{ route('selflearning.index') }}" class="btn btn-outline-secondary btn-sm  selflearning-back-btn">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- コースヘッダー --}}
    <div class="mb-4">
        <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}"
             class="course-header-image rounded mb-3">
        <h2 class="fw-bold">{{ $course->title }}</h2>
        <p class="course-meta">
            {{ $course->sections->count() }} sections ・
            {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} lectures ・
            {{ gmdate('H', $course->sections->sum(fn($s) => $s->lessons->sum('duration')) * 60) }} hours
        </p>
    </div>

    {{-- セクション一覧 --}}
    <div class="accordion course-accordion" id="courseAccordion">
        @foreach($course->sections as $index => $section)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                        <span class="fw-bold">{{ $section->title }}</span>
                        <span class="ms-auto section-meta">
                            {{ $section->lessons->count() }} lectures ・
                            {{ $section->lessons->sum('duration') }} min
                        </span>
                    </button>
                </h2>
                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                     data-bs-parent="#courseAccordion">
                    <div class="accordion-body">
                        @foreach($section->lessons as $lesson)
                            <div class="lesson-item d-flex justify-content-between align-items-center">
                                <span class="lesson-title">
                                    {{-- 🎥 動画アイコン + 📖 テキストアイコン 両方表示 --}}
                                        <i class="fa-solid fa-video me-2 text-dark"></i>
                                        <i class="fa-solid fa-book me-2 text-dark"></i>
                                        {{ $lesson->title }}
                                    </span>
                                    <div class="lesson-actions d-flex align-items-center">
                                        <span class="lesson-duration">{{ $lesson->duration }} min</span>

                                    {{-- ▶ 再生ボタン --}}
                                    <a href="{{ route('selflearning.lessonVideo', ['courseId' => $course->id, 'lessonId' => $lesson->id]) }}" 
                                       class="btn btn-outline-primary shadow-sm ms-2">
                                        <i class="fa-solid fa-play"></i>
                                    </a>

                                    {{-- 📖 テキストボタン --}}
                                    <a href="{{ route('selflearning.lesson.text', ['courseId' => $course->id, 'lessonId' => $lesson->id]) }}" 
                                       class="btn btn-outline-info btn-info  shadow-sm ms-2">
                                        Text
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
