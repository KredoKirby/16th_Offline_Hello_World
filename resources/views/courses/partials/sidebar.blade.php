@foreach($courses as $course)
    @php
        $active = (bool)($course->is_active ?? true);
        $img = $course->photo
            ? asset('storage/' . $course->photo)
            : asset('images/course-default.png');
    @endphp

    <div class="list-group-item border-0 p-0 mb-2">

        <div class="d-flex align-items-center p-2 rounded border shadow-sm">

            <img src="{{ $img }}" class="rounded-circle me-3"
                 width="48" height="48" style="object-fit:cover;">

            <div class="flex-grow-1 fw-semibold">
                {{ $course->name }}
            </div>

            <div class="me-3 d-flex align-items-center">
                <span class="status-dot {{ $active ? 'bg-success' : 'bg-secondary' }}"></span>
                <span class="ms-1">{{ $active ? 'Active' : 'Inactive' }}</span>
            </div>

            <form method="POST" action="{{ route('admin.courses.toggle', $course) }}" class="me-2">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $active ? 'btn-secondary' : 'btn-success' }}">
                    {{ $active ? 'Inactivate' : 'Activate' }}
                </button>
            </form>

            <button class="btn btn-link text-decoration-none p-0"
                data-bs-toggle="collapse"
                data-bs-target="#topics-{{ $course->id }}">
                <span class="caret"></span>
            </button>
        </div>

        <div id="topics-{{ $course->id }}" class="collapse mt-2">
            @include('admin.courses.partials.topics', compact('course'))
        </div>
    </div>
@endforeach

<style>
.status-dot{display:inline-block;width:12px;height:12px;border-radius:50%}
.caret::after{content:'▾';transition:.2s}
[aria-expanded="true"] .caret::after{transform:rotate(180deg)}
</style>
