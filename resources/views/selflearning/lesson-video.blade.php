@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="row">

        {{-- 左：動画エリア --}}
        <div class="col-lg-9 col-12 mb-4 mb-lg-0">
            {{-- 戻るボタン --}}
            <div class="mb-3">
                <a href="{{ route('selflearning.show', $course->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </a>
            </div>

            {{-- タイトル --}}
            <div class="video-player-header text-muted mb-2">
                {{ $course->title }} &lt; {{ $currentLesson->title }}
            </div>

            {{-- 動画 --}}
            <div class="video-container mb-3">
                <video class="w-100" controls style="max-height:580px;">
                    <source src="{{ asset('videos/' . $currentLesson->video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            {{-- ナビボタン --}}
            <div class="lesson-buttons d-flex flex-wrap gap-2">
                {{-- Previous --}}
                @if($previousLesson)
                    <a href="{{ route('selflearning.lessonVideo', [$course->id, $previousLesson->id]) }}" 
                       class="btn btn-secondary shadow flex-fill text-center">Previous</a>
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
                       class="btn btn-primary shadow flex-fill text-center">Next</a>
                @else
                    <button class="btn btn-primary shadow flex-fill" disabled>Next</button>
                @endif
            </div>
        </div>

        {{-- 右：Video List --}}
        <div class="col-lg-3 col-12">
            <div class="border-start bg-white p-3" style="min-height: 500px; overflow-y:auto;">
                <h5 class="fw-bold mb-3">Video List</h5>

                <div class="accordion" id="videoAccordion">
                    @foreach($course->sections as $sIndex => $section)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingVideo{{ $sIndex }}">
                                <button class="accordion-button {{ $sIndex > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseVideo{{ $sIndex }}">
                                    {{ $loop->iteration }}. {{ $section->title }}
                                    <span class="ms-auto small text-muted">
                                        {{ $section->lessons->count() }} videos ・ {{ $section->lessons->sum('duration') }} min
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseVideo{{ $sIndex }}"
                                 class="accordion-collapse collapse {{ $sIndex == 0 ? 'show' : '' }}"
                                 data-bs-parent="#videoAccordion">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        @foreach($section->lessons as $l)
                                        <li class="mb-2 d-flex align-items-center flex-wrap">
                                            {{-- アイコン --}}
                                            <i class="lesson-status-icon me-2 
                                                {{ auth()->user()->completedLessons->contains($l->id) ? 'fa-solid fa-check-circle text-success' : 'fa-regular fa-circle-play text-muted' }}"
                                            data-lesson-id="{{ $l->id }}"></i>

                                            <input type="checkbox" class="lesson-checkbox d-none"
                                                data-course-id="{{ $course->id}}"
                                                data-lesson-id="{{ $l->id}}"
                                                {{ auth()->user()->completedLessons->contains($l->id) ? 'checked' : '' }}>

                                            <a href="{{ route('selflearning.lessonVideo', ['courseId'=>$course->id,'lessonId'=>$l->id]) }}"
                                               class="flex-grow-1 text-decoration-none {{ $l->id == $currentLesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                                {{ $l->title }}
                                            </a>

                                            <span class="ms-auto small text-muted">{{ $l->duration }} min</span>
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
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".lesson-status-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            const lessonId = this.dataset.lessonId;
            const cb = document.querySelector(`.lesson-checkbox[data-lesson-id="${lessonId}"]`);
            const courseId = cb.dataset.courseId;

            cb.checked = !cb.checked;

            fetch(`/selflearning/${courseId}/lesson/${lessonId}/toggle`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === "checked") {
                    this.className = "lesson-status-icon fa-solid fa-check-circle text-success me-2";
                } else {
                    this.className = "lesson-status-icon fa-regular fa-circle-play text-muted me-2";
                }
            })
            .catch(err => console.error("toggle error:", err));
        });
    });
});
</script>
@endpush
@endsection
