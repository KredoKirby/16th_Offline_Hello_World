@extends('layouts.app')
@section('title', 'Course Detail')

@section('content')
<div class="container-fluid py-3">
  <div class="card shadow-sm">
    <div class="card-body">
      {{-- コース名（titleカラム） --}}
      <h3 class="fw-bold mb-3">{{ $course->title }}</h3>

      <hr>

      <h5 class="fw-bold">Topics</h5>
      <ul class="list-group mb-3">
        @forelse($course->topics as $topic)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $topic->title }}
          </li>
        @empty
          <li class="list-group-item text-muted">No topics yet</li>
        @endforelse
      </ul>

      <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">← Back</a>
      <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-primary">Edit</a>
    </div>
  </div>
</div>
@endsection
