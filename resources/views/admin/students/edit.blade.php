@extends('layouts.admin')
@section('title', 'Edit Student')

@section('content')
<h2 class="mb-4 fw-bold text-dark">Edit Student</h2>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Avatar URL</label>
        <input type="url" name="avatar" class="form-control" value="{{ $student->avatar }}">
      </div>

      <div class="form-check form-switch mb-4">
        <input class="form-check-input" type="checkbox" id="activeSwitch" name="active"
               {{ $student->active ? 'checked' : '' }}>
        <label class="form-check-label" for="activeSwitch">Active</label>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        <button type="submit" class="btn px-4" style="background-color:#189AB4; color:white;">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection
