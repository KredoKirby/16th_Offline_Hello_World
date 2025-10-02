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
        {{-- 左側：動画再生エリア --}}
        <div class="col-md-9 p-3">
            {{-- ページヘッダー（コース > レッスン） --}}
            <div class="video-player-header text-muted mb-2">
                {{ $course->title }} &lt; {{ $currentLesson->title }}
            </div>

            {{-- 動画プレイヤー --}}
            <div class="video-container mb-2">
                <video width="100%" height="580" controls>
                    <source src="{{ asset('videos/' . $currentLesson->video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            {{-- ナビゲーションボタン（等幅） --}}
            <div class="lesson-buttons d-flex gap-2 mt-3">
                {{-- Previous --}}
                @if($previousLesson)
                    <a href="{{ route('selflearning.lessonVideo', [$course->id, $previousLesson->id]) }}" 
                       class="btn btn-secondary shadow flex-fill text-center">
                        Previous
                    </a>
                @else
                    <button class="btn btn-secondary shadow flex-fill" disabled>Previous</button>
                @endif

                {{-- Done --}}
                <form action="{{ route('selflearning.lesson.done', [$course->id, $currentLesson->id]) }}" 
                      method="POST" class="flex-fill">
                    @csrf
                    <button type="submit" class="btn btn-success shadow w-100">Done</button>
                </form>

                {{-- Next --}}
                @if($nextLesson)
                    <a href="{{ route('selflearning.lessonVideo', [$course->id, $nextLesson->id]) }}" 
                       class="btn btn-primary shadow flex-fill text-center">
                        Next
                    </a>
                @else
                    <button class="btn btn-primary shadow flex-fill" disabled>Next</button>
                @endif
            </div>
        </div> {{-- ← col-md-9 を閉じる --}}

        {{-- 右：アコーディオン形式の Video List --}}
        <div class="col-md-3 border-start bg-white p-3" style="min-height:100vh; overflow-y:auto;">
            <h5 class="fw-bold mb-3">Video List</h5>

            <div class="accordion" id="videoAccordion">
                @foreach($course->sections as $sIndex => $section)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingVideo{{ $sIndex }}">
                            <button class="accordion-button {{ $sIndex > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseVideo{{ $sIndex }}">
                                {{ $loop->iteration }}. {{ $section->title }}
                                <span class="ms-auto small text-muted">
                                    {{ $section->lessons->count() }} videos ・ 
                                    {{ $section->lessons->sum('duration') }} min
                                </span>
                            </button>
                        </h2>
                        <div id="collapseVideo{{ $sIndex }}"
                             class="accordion-collapse collapse {{ $sIndex == 0 ? 'show' : '' }}"
                             data-bs-parent="#videoAccordion">
                            <div class="accordion-body">

                                {{-- Video List --}}
                                    <ul class="list-unstyled">
                                        @foreach($section->lessons as $l)
                                            <li class="mb-2">
                                                <a href="{{ route('selflearning.lessonVideo', ['courseId' => $course->id, 'lessonId' => $l->id]) }}"
                                                class="d-flex align-items-center text-decoration-none 
                                                        {{ $l->id == $currentLesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                                    
                                                    {{-- アイコン: 完了済みならチェック, まだなら再生 --}}
                                                    @if(auth()->user()->completedLessons->contains($l->id))
                                                        <i class="fa-solid fa-check-circle me-2 text-success"></i>
                                                    @else
                                                        <i class="fa-regular fa-circle-play me-2 text-muted"></i>
                                                    @endif

                                                    {{ $l->title }}
                                                    <span class="ms-auto small text-muted">{{ $l->duration }} min</span>
                                                </a>
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
