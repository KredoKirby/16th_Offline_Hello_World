@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド: コース一覧 --}}
        <div class="col-md-3 border-end">
            <h3 class="fw-bold">Courses</h3>

            <ul class="nav nav-pills mb-3">
                <li class="nav-item"><a class="nav-link active" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Active</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Completed</a></li>
            </ul>

            @foreach($courses as $c)
            <div class="card mb-3 shadow-sm">
                <img src="{{ $c->image_url ?? 'https://via.placeholder.com/300x150' }}" 
                     class="card-img-top" alt="{{ $c->title }}">
                <div class="card-body p-2">
                    <h6 class="card-title mb-1">{{ $c->title }}</h6>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ rand(10,80) }}%;"></div>
                    </div>
                    <small class="text-muted">{{ rand(10,80) }}% Finish</small>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 右サイド: コース詳細 --}}
        <div class="col-md-9 ps-4">

            {{-- コースヘッダー --}}
            <img src="{{ $course->image_url ?? 'https://via.placeholder.com/800x200' }}" 
                 alt="{{ $course->title }}" class="img-fluid rounded mb-3">

            <h3 class="fw-bold">{{ $course->title }}</h3>

            {{-- 進捗バー --}}
            <div class="mb-4">
                <div class="progress" style="height: 10px;">
                    <div id="overall-progress" class="progress-bar bg-success" role="progressbar" style="width: 0%;">
                    </div>
                </div>
                <small id="progress-text">0% Completed</small>
            </div>

            {{-- Accordion: セクション & レッスン --}}
            <div class="accordion" id="lessonAccordion">
                @foreach($course->sections as $section)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $section->id }}">
                        <button class="accordion-button collapsed" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse{{ $section->id }}">
                            {{ $loop->iteration }}. {{ $section->title }}
                            <span class="ms-2 text-muted" id="section-progress-{{ $section->id }}">
                                0/{{ $section->lessons->count() }}
                            </span>
                        </button>
                    </h2>
                    <div id="collapse{{ $section->id }}" class="accordion-collapse collapse" 
                         data-bs-parent="#lessonAccordion">
                        <div class="accordion-body">
                            <ul class="list-unstyled">
                                @foreach($section->lessons as $lesson)
                                <li class="mb-1">
                                    <input type="checkbox" 
                                           class="form-check-input me-2 lesson-checkbox" 
                                           data-lesson-id="{{ $lesson->id }}"
                                           data-section="{{ $section->id }}"
                                           {{ $lesson->isCompletedBy(auth()->user()) ? 'checked' : '' }}>
                                    {{ $lesson->title }}
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

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".lesson-checkbox");
    const progressBar = document.getElementById("overall-progress");
    const progressText = document.getElementById("progress-text");

    function updateProgress() {
        let total = checkboxes.length;
        let completed = document.querySelectorAll(".lesson-checkbox:checked").length;
        let percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        progressBar.style.width = percent + "%";
        progressText.textContent = percent + "% Completed";

        // セクションごとの進捗
        let sectionGroups = {};
        checkboxes.forEach(cb => {
            let sectionId = cb.dataset.section;
            if (!sectionGroups[sectionId]) {
                sectionGroups[sectionId] = {total: 0, done: 0};
            }
            sectionGroups[sectionId].total++;
            if (cb.checked) sectionGroups[sectionId].done++;
        });

        Object.keys(sectionGroups).forEach(id => {
            let el = document.getElementById("section-progress-" + id);
            el.textContent = `${sectionGroups[id].done}/${sectionGroups[id].total}`;
        });
    }

    // Ajax保存
    function saveProgress(lessonId, completed) {
        fetch(`/lessons/${lessonId}/progress`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                completed: completed ? 1 : 0
            })
        })
        .then(res => res.json())
        .then(data => console.log("Saved:", data))
        .catch(err => console.error(err));
    }

    checkboxes.forEach(cb => cb.addEventListener("change", function () {
        updateProgress();
        saveProgress(this.dataset.lessonId, this.checked);
    }));

    // 初期化
    updateProgress();
});
</script>
@endpush
