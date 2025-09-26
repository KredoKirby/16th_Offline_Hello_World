{{-- resources/views/admin/teachers/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Teacher')

@section('content')
<div class="container py-3">
  <h4 class="mb-3">Edit Teacher</h4>

  <form method="POST" action="{{ route('admin.teachers.update', $t->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" value="{{ old('name',$t->name) }}" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" value="{{ old('email',$t->email) }}" class="form-control">
    </div>

    <div class="form-check form-switch mb-4">
      <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" {{ $t->active ? 'checked' : '' }}>
      <label class="form-check-label" for="active">Active</label>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Cancel</a>
      <button class="btn btn-primary">Save</button>
    </div>
  </form>
</div>
@endsection
