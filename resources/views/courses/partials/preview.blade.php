{{-- 未受講コースのプレビュー --}}
<div class="course-preview">

    {{-- カバー画像 --}}
    <img src="{{ $course->image ?? 'https://via.placeholder.com/800x200' }}" 
         alt="{{ $course->title }}" 
         class="img-fluid rounded mb-4">

    {{-- タイトル --}}
    <h2 class="fw-bold">{{ $course->title }}</h2>

    {{-- サブタイトル / 説明 --}}
    <p class="text-muted">{{ $course->description ?? "In this course, you will learn..." }}</p>

    {{-- Enroll ボタン --}}
    <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mb-4">
        @csrf
        <button type="submit" class="btn btn-success rounded-pill px-4">
            Enroll a course
        </button>
    </form>

    {{-- Course content --}}
    <h5 class="fw-bold">Course’s content</h5>
    <p class="text-muted">{{ $course->sections->count() }} lessons</p>

    {{-- Section リスト --}}
    <div class="accordion" id="previewAccordion">
        @foreach($course->sections as $section)
        <div class="accordion-item mb-2 border rounded">
            <h2 class="accordion-header" id="headingPrev{{ $section->id }}">
                <button class="accordion-button collapsed bg-white" type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapsePrev{{ $section->id }}">
                    <div class="d-flex align-items-center">
                        {{-- アイコンやサムネ --}}
                        <img src="https://via.placeholder.com/40x40" 
                             class="me-2 rounded" alt="icon">
                        <span>{{ $section->title }}</span>
                    </div>
                </button>
            </h2>
            <div id="collapsePrev{{ $section->id }}" 
                 class="accordion-collapse collapse" 
                 data-bs-parent="#previewAccordion">
                <div class="accordion-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($section->lessons as $lesson)
                        <li class="mb-1 d-flex align-items-center">
                            <i class="bi bi-play-circle me-2 text-secondary"></i>
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
