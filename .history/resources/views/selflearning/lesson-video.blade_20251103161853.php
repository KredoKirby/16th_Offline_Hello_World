@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="row">

        {{-- 左：動画エリア --}}
        <div class="col-lg-9 col-12 mb-4 mb-lg-0">
            {{-- 戻るボタン  --}}
            <div class="mb-3 d-flex justify-content-between align-items-center">
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
                <video id="lessonVideo" class="w-100" controls style="max-height:580px;">
                    <source src="{{ asset('videos/' . $currentLesson->video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            {{-- ナビボタン --}}
            <div class="lesson-buttons d-flex flex-wrap gap-2">
                @if($previousLesson)
                    <a href="{{ route('selflearning.lessonVideo', [$course->id, $previousLesson->id]) }}" 
                       class="btn btn-secondary shadow flex-fill text-center">Previous</a>
                @else
                    <button class="btn btn-secondary shadow flex-fill" disabled>Previous</button>
                @endif

                <form action="{{ route('selflearning.lesson.done', [$course->id, $currentLesson->id]) }}" method="POST" class="flex-fill">
                    @csrf
                    <button type="submit" class="btn btn-success shadow w-100">Done</button>
                </form>

                @if($nextLesson)
                    <a href="{{ route('selflearning.lessonVideo', [$course->id, $nextLesson->id]) }}" 
                       class="btn btn-primary shadow flex-fill text-center">Next</a>
                @else
                    <button class="btn btn-primary shadow flex-fill" disabled>Next</button>
                @endif
            </div>
        </div>

      {{-- 右Video List --}}
<div class="col-lg-3 col-12">
    <div class="border-start bg-white p-3" style="min-height: 500px; overflow-y:auto;">
        <h5 class="fw-bold mb-3">Video List</h5>

        <div class="accordion" id="videoAccordion">
            @foreach($course->topics as $sIndex => $section)
                @php
                    // セクション合計時間（秒 → mm:ss）
                    $totalSeconds = $section->lessons->sum('duration');
                    $totalMinutes = floor($totalSeconds / 60);
                    $totalRemainSeconds = $totalSeconds % 60;
                @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingVideo{{ $sIndex }}">
                        <button class="accordion-button {{ $sIndex > 0 ? 'collapsed' : '' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseVideo{{ $sIndex }}">
                            {{ $loop->iteration }}. {{ $topic->title }}
                            <span class="ms-auto small text-muted">
                                {{ $topic->lessons->count() }} videos ・
                                {{ sprintf('%02d:%02d', $totalMinutes, $totalRemainSeconds) }}
                            </span>
                        </button>
                    </h2>
                    <div id="collapseVideo{{ $sIndex }}"
                         class="accordion-collapse collapse {{ $sIndex == 0 ? 'show' : '' }}"
                         data-bs-parent="#videoAccordion">
                        <div class="accordion-body">
                            <ul class="list-unstyled">
                                @foreach($topic->lessons as $l)
                                    @php
                                        $lMinutes = floor($l->duration / 60);
                                        $lSeconds = $l->duration % 60;
                                    @endphp
                                    <li class="mb-2 d-flex align-items-center flex-wrap">
                                        <i class="lesson-status-icon me-2 
                                            {{ auth()->user()->completedLessons->contains($l->id) ? 'fa-solid fa-check-circle text-success' : 'fa-regular fa-circle-play text-muted' }}"
                                        data-lesson-id="{{ $l->id }}"></i>

                                        <a href="{{ route('selflearning.lessonVideo', ['courseId'=>$course->id,'lessonId'=>$l->id]) }}"
                                           class="flex-grow-1 text-decoration-none {{ $l->id == $currentLesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                            {{ $l->title }}
                                        </a>

                                        {{-- 秒 → mm:ss 形式 --}}
                                        <span class="ms-auto small text-muted">
                                            {{ sprintf('%02d:%02d', $lMinutes, $lSeconds) }}
                                        </span>
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
    const video = document.getElementById("lessonVideo");
    const hoursEl = document.getElementById("hoursLearned");
    let lastTime = null;
    let bufferSeconds = 0;

    if (!video) return;

    // play 開始時に基準時刻をセット
    video.addEventListener('play', () => {
        lastTime = video.currentTime;
    });

    // timeupdate は高頻度で来る（1秒未満ごと）
    video.addEventListener('timeupdate', () => {
        if (video.paused || video.ended) return;
        if (lastTime === null) lastTime = video.currentTime;
        const now = video.currentTime;
        let delta = now - lastTime;
        // 異常値防止
        if (delta < 0) delta = 0;
        if (delta > 3) delta = 3; // シーク等で不正に大きくならないように制限
        bufferSeconds += delta;
        lastTime = now;

        // 送信閾値：10秒以上たまったら送る
        if (bufferSeconds >= 3) {
            const sendSeconds = Math.floor(bufferSeconds);
            bufferSeconds = 0;
            sendStudyTime(sendSeconds);
        }
    });

    // ページ離脱時の送信
    window.addEventListener("beforeunload", () => {
        if (bufferSeconds > 0) {
            navigator.sendBeacon("{{ route('selflearning.updateTime') }}", JSON.stringify({
                lesson_id: {{ $currentLesson->id }},
                seconds: Math.floor(bufferSeconds)
            }));
        }
    });

    function sendStudyTime(seconds) {
        console.log('sendStudyTime:', seconds);
        fetch("{{ route('selflearning.updateTime') }}", {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                lesson_id: {{ $currentLesson->id }},
                seconds: seconds
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok ' + res.status);
            return res.json();
        })
        .then(data => {
            console.log("Study time updated response:", data);
            if (data.formatted_time && hoursEl) {
                hoursEl.textContent = data.formatted_time;
            }
        })
        .catch(err => {
            console.error("Error updating study time:", err);
        });
    }
});
</script>

@endpush
@endsection
