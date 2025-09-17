<div class="card shadow-sm border-0">
    <img src="{{ $course->image ?? 'https://via.placeholder.com/800x200' }}" 
         alt="{{ $course->title }}" 
         class="img-fluid rounded mb-3">
    <div class="card-body">
        <h3 class="fw-bold">{{ $course->title }}</h3>
        <p class="text-muted">{{ $course->description }}</p>

        <form action="{{ route('courses.enroll', $course) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-info rounded-pill px-4">Enroll a course</button>
        </form>

        <h6 class="fw-bold mt-4">Course’s content</h6>
        <div class="border rounded p-2 mb-2">Introduction</div>
        <div class="border rounded p-2">Variables</div>
    </div>
</div>
