<div>
    <img src="{{ $course->image ?? 'https://via.placeholder.com/800x200' }}" 
         alt="{{ $course->title }}" 
         class="img-fluid rounded mb-3">

    <h3 class="fw-bold">{{ $course->title }}</h3>

    {{-- Accordion: セクションとレッスン --}}
    <div class="accordion mt-3" id="lessonAccordion">
        @foreach($course->sections as $section)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $section->id }}">
                <button class="accordion-button collapsed" type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse{{ $section->id }}">
                    {{ $loop->iteration }}. {{ $section->title }}
                    <span class="ms-2 text-muted">0/{{ $section->lessons->count() }}</span>
                </button>
            </h2>
            <div id="collapse{{ $section->id }}" class="accordion-collapse collapse" 
                 data-bs-parent="#lessonAccordion">
                <div class="accordion-body">
                    <ul class="list-unstyled">
                        @foreach($section->lessons as $lesson)
                        <li class="mb-1">
                            <input type="checkbox" class="form-check-input me-2">
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
