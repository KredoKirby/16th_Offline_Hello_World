@extends('layouts.app')

@section('content')
<div class="container">

    {{-- コースヘッダー --}}
    <div class="mb-4">
        <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}"
             class="w-100 rounded mb-3" style="max-height:300px; object-fit:cover;">
        <h2 class="fw-bold">{{ $course->title }}</h2>
        <p>
            {{ $course->sections->count() }} sections ・
            {{ $course->sections->sum(fn($s) => $s->lessons->count()) }} lectures ・
            {{ gmdate('H', $course->sections->sum(fn($s) => $s->lessons->sum('duration')) * 60) }} hours
        </p>
    </div>

    {{-- セクション一覧 --}}
    <div class="accordion" id="courseAccordion">
        @foreach($course->sections as $index => $section)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                        {{ $section->title }}
                        <span class="ms-auto small text-muted">
                            {{ $section->lessons->count() }} lectures ・
                            {{ $section->lessons->sum('duration') }} minutes
                        </span>
                    </button>
                </h2>
                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                     data-bs-parent="#courseAccordion">
                    <div class="accordion-body">
                        @foreach($section->lessons as $lesson)
                            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                <span>
                                    {{-- 🎥 動画アイコン + 📖 テキストアイコン --}}
                                    <i class="fa-solid fa-video me-2"></i>
                                    <i class="fa-solid fa-book me-2"></i>
                                    {{ $lesson->title }}
                                </span>
                                <div class="d-flex align-items-center">
                                    {{-- 再生時間 --}}
                                    <span class="text-muted me-2">{{ $lesson->duration }} min</span>
                                    {{-- ▶ 再生ボタン --}}
                                    <a href="#" class="btn btn-sm btn-outline-dark">
                                        <i class="fa-solid fa-play"></i>
                                    </a>
                                    {{-- テキストリンク --}}
                                    <a href="#" class="btn btn-sm btn-info ms-2">text</a>
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
