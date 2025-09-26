@extends('layouts.admin')
@section('title', 'Course Detail')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="fw-bold mb-3">{{ $course['name'] ?? 'Course Name' }}</h3>

            <p>
                Status:
                @if($course['active'])
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </p>

            <hr>

            <h5 class="fw-bold">Topics</h5>
            <ul class="list-group mb-3">
                @forelse($course['topics'] ?? [] as $topic)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $topic['name'] ?? 'Topic Name' }}
                        @if($topic['active'])
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-muted">No topics yet</li>
                @endforelse
            </ul>

            <a href="{{ route('admin.courses') }}" class="btn btn-outline-secondary">← Back</a>
            <a href="{{ route('admin.courses.edit', $course['id']) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>
@endsection
