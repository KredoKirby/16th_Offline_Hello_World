@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- 戻るボタン --}}
                <div class="mb-3">
                    <a href="{{ route('selflearning.show', $course->id) }}" class="btn btn-outline-secondary btn-sm  selflearning-back-btn">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back
                    </a>
                </div>

        {{-- 左：スライドやテキスト表示エリア --}}
        <div class="col-md-9 p-3">
         
            <div class="mb-3 text-muted">
                {{ $course->title }} <span class="mx-2">&lt;</span> {{ $currentLesson->title }}
            </div>
            

            {{-- スライド / テキスト表示枠 --}}
            <div class="bg-white shadow-sm rounded p-4" style="min-height:70vh;">
                {!! $currentLesson->content !!}  
                {{-- スライド画像 / HTML本文 --}}
            </div>
        </div>

        {{-- 右：アコーディオン形式の TOC --}}
        <div class="col-md-3 border-start bg-white p-3" style="min-height:100vh; overflow-y:auto;">
            <h5 class="fw-bold mb-3">Table of contents</h5>

            <div class="accordion" id="textAccordion">
                @foreach($course->sections as $sIndex => $section)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingText{{ $sIndex }}">
                            <button class="accordion-button {{ $sIndex > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseText{{ $sIndex }}">
                                {{ $loop->iteration }}. {{ $section->title }}
                                <span class="ms-auto small text-muted">
                                    {{ $section->lessons->count() }} lessons ・ {{ $section->lessons->sum('pages') }} pages
                                </span>
                            </button>
                        </h2>
                        <div id="collapseText{{ $sIndex }}"
                             class="accordion-collapse collapse {{ $sIndex == 0 ? 'show' : '' }}"
                             data-bs-parent="#textAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    @foreach($section->lessons as $lesson)
                                        <li class="mb-2 d-flex align-items-center">

                                            {{-- チェックボックス --}}
                                            <input type="checkbox"
                                                   class="lesson-checkbox me-2"
                                                   data-lesson-id="{{ $lesson->id }}"
                                                   {{ $lesson->completed ? 'checked' : '' }}>

                                            {{-- レッスンタイトル --}}
                                            <a href="{{ route('selflearning.lesson.text', ['courseId' => $course->id, 'lessonId' => $lesson->id]) }}"
                                               class="flex-grow-1 text-decoration-none {{ $lesson->id == $currentLesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                                {{ $lesson->title }}
                                            </a>

                                            {{-- ページ数 --}}
                                            <span class="ms-auto small text-muted">{{ $lesson->pages }} pages</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>


@endsection
