@extends('layouts.admin')
@section('title', 'Add Student')

@section('content')
<h2 class="mb-4 fw-bold text-dark">Add Student</h2>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.students.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold">Name</label>
        <input type="text" name="name" class="form-control" required placeholder="e.g. David">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" required placeholder="e.g. david@gmail.com">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Avatar URL</label>
        <input type="url" name="avatar" class="form-control" placeholder="https://example.com/avatar.jpg">
      </div>

      <div class="form-check form-switch mb-4">
        <input class="form-check-input" type="checkbox" id="activeSwitch" name="active" checked>
        <label class="form-check-label" for="activeSwitch">Active</label>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        <button type="submit" class="btn px-4" style="background-color:#189AB4; color:white;">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
