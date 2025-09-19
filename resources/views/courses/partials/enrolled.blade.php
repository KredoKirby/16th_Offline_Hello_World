{{-- 受講中コースの進捗UI --}}
<div class="course-enrolled">

    {{-- カバー画像 --}}
    <img src="{{ $course->image ?? 'https://via.placeholder.com/800x200' }}" 
         alt="{{ $course->title }}" 
         class="img-fluid rounded mb-3" style="max-height:250px; object-fit:cover; width:100%;">

    {{-- タイトル --}}
    <h3 class="fw-bold mb-4">{{ $course->title }}</h3>

    {{-- Accordion: セクションごとのレッスンリスト --}}
    <div class="accordion" id="lessonAccordion">
        @foreach($course->sections as $section)
        <div class="accordion-item mb-2 border">
            <h2 class="accordion-header" id="heading{{ $section->id }}">
                <button class="accordion-button collapsed d-flex justify-content-between" 
                        type="button" data-bs-toggle="collapse" 
                        data-bs-target="#collapse{{ $section->id }}">
                    <span>{{ $loop->iteration }}. {{ $section->title }}</span>
                    <small class="text-muted" id="section-progress-{{ $section->id }}">
                        {{ $sectionProgress[$section->id]['completed'] }}/{{ $sectionProgress[$section->id]['total'] }}
                    </small>
                </button>
            </h2>
            <div id="collapse{{ $section->id }}" 
                 class="accordion-collapse collapse" 
                 data-bs-parent="#lessonAccordion">
                <div class="accordion-body p-3">
                    <ul class="list-unstyled mb-0">
                        @foreach($section->lessons as $lesson)
                        <li class="mb-2 d-flex align-items-center">
                            <input type="checkbox" 
                                   class="form-check-input me-2 lesson-checkbox" 
                                   data-lesson-id="{{ $lesson->id }}"
                                   data-section="{{ $section->id }}"
                                   {{ in_array($lesson->id, $completedLessonIds) ? 'checked' : '' }}>
                            <span>{{ $lesson->title }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".lesson-checkbox");

    function updateProgress() {
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

    function saveProgress(lessonId, completed) {
        fetch(`/lessons/${lessonId}/progress`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ completed: completed ? 1 : 0 })
        })
        .then(res => res.json())
        .then(data => console.log("Saved:", data))
        .catch(err => console.error(err));
    }

    checkboxes.forEach(cb => cb.addEventListener("change", function () {
        updateProgress();
        saveProgress(this.dataset.lessonId, this.checked);
    }));

    updateProgress();
});
</script>
@endpush
