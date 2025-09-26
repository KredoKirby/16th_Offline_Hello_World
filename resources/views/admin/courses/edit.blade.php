@extends('layouts.admin')
@section('title', 'Edit Course')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="fw-bold mb-3">Edit Course</h3>

            <form method="post" action="{{ route('admin.courses.update', $course['id']) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Course Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $course['name'] ?? '') }}">
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="active" id="active"
                           class="form-check-input"
                           {{ old('active', $course['active'] ?? false) ? 'checked' : '' }}>
                    <label for="active" class="form-check-label">Active</label>
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('admin.courses') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
