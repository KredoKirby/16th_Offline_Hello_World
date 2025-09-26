<div>
    {{-- コースヘッダー画像 --}}
    <div class="mb-3">
        @if($course->image)
            <img src="{{ asset('images/courses/' . $course->image) }}" 
                 class="course-header-image rounded"
                 alt="{{ $course->title }}">
        @else
            <div class="bg-secondary text-white text-center p-5 rounded">
                No Image
            </div>
        @endif
    </div>

            {{-- コースタイトル & アクション --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="fw-bold mb-0">{{ $course->title }}</h3>

                <!-- Unenroll ボタンだけ置く -->
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#unenrollModal-{{ $course->id }}">
                    Unenroll
                </button>
            </div>

                <!-- モーダルはフレックスの外に配置 -->
                <div class="modal fade" id="unenrollModal-{{ $course->id }}" tabindex="-1" aria-labelledby="unenrollModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unenrollModalLabel">Confirm Unenroll</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to unenroll from <strong>{{ $course->title }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('courses.unenroll', $course->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Yes, Unenroll</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </div>
                </div>
                </div>

                        </div>

                        <p class="text-muted">Overall Progress: {{ $coursePercent }}%</p>
                        <div class="progress mb-4" style="height:8px;">
                            <div class="progress-bar bg-info" style="width: {{ $coursePercent }}%;"></div>
                        </div>


    {{-- セクションごとのアコーディオン --}}
    <div class="accordion mt-3" id="courseAccordion">
        @foreach($course->sections as $sectionIndex => $section)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $section->id }}">
                    <button class="accordion-button collapsed" type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse{{ $section->id }}" 
                            aria-expanded="false" 
                            aria-controls="collapse{{ $section->id }}">
                        {{ $loop->iteration }}. {{ $section->title }}
                        <span class="ms-2 text-muted small">
                            {{ $sectionProgress[$section->id]['completed'] ?? 0 }}/{{ $sectionProgress[$section->id]['total'] ?? 0 }}
                        </span>
                    </button>
                </h2>
                <div id="collapse{{ $section->id }}" class="accordion-collapse collapse" 
                     aria-labelledby="heading{{ $section->id }}" 
                     data-bs-parent="#courseAccordion">
                    <div class="accordion-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($section->lessons as $lesson)
                                <li class="d-flex align-items-center mb-2">
                                    <form method="POST" 
                                          action="{{ route('lessons.toggle', [$course->id, $lesson->id]) }}"
                                          class="lesson-toggle-form me-2">
                                        @csrf
                                        <input type="checkbox" class="form-check-input lesson-checkbox"
                                               onchange="this.form.submit()"
                                               {{ in_array($lesson->id, $completedLessonIds ?? []) ? 'checked' : '' }}  disabled>
                                    </form>
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
