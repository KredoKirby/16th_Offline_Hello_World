<div class="mt-3">
    <h5 class="fw-bold">Lessons</h5>
    <ul class="list-group">
        @foreach($course->sections as $section)
            <li class="list-group-item">
                <strong>{{ $section->title }}</strong>

                <ul class="list-group mt-2">
                    @foreach($section->lessons as $lesson)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span>{{ $lesson->title }}</span>

                            {{-- チェックボックス UI --}}
                            <input type="checkbox"
                                   class="form-check-input mark-complete"
                                   data-lesson-id="{{ $lesson->id }}"
                                   {{ in_array($lesson->id, $completedLessonIds) ? 'checked' : '' }}>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>

{{-- JSで進捗更新 --}}
<script>
document.querySelectorAll('.mark-complete').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        fetch(`/lessons/${this.dataset.lessonId}/progress`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                completed: this.checked ? 1 : 0
            })
        }).then(res => res.json())
          .then(data => console.log(data));
    });
});
</script>
