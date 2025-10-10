@extends('layouts.admin')
@section('title', 'Student Detail')

@section('content')
<h2 class="mb-4 fw-bold text-dark">Student Detail</h2>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex align-items-center mb-4">
      <img src="{{ $student->avatar }}" alt="Avatar" class="rounded-circle me-3 border" width="72" height="72">
      <div>
        <h4 class="fw-bold mb-0">{{ $student->name }}</h4>
        <p class="text-muted mb-0">{{ $student->email }}</p>
      </div>
      <div class="ms-auto">
        @if ($student->active)
          <span class="badge rounded-pill bg-success-subtle border border-success text-success px-3 py-2">Active</span>
        @else
          <span class="badge rounded-pill bg-secondary-subtle border border-secondary text-secondary px-3 py-2">Inactive</span>
        @endif
      </div>
    </div>

    <dl class="row mb-4">
      <dt class="col-sm-3">Created At</dt>
      <dd class="col-sm-9">{{ $student->created_at }}</dd>

      <dt class="col-sm-3">Updated At</dt>
      <dd class="col-sm-9">{{ $student->updated_at }}</dd>
    </dl>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-4">Back</a>
      <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-outline-primary px-4">Edit</a>

      <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}"
            onsubmit="return confirm('Are you sure to delete this student?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger px-4">Delete</button>
      </form>
    </div>
  </div>
</div>
@endsection
