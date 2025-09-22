@extends('layouts.admin')
@section('title','Add Course')

@section('content')
  <h2 class="mb-3">Add Course</h2>

  @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.courses.store') }}" class="card p-3">
    @csrf
    <label class="mb-2">Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required class="mb-4">

    <div style="display:flex; gap:8px">
      <button class="btn dark" type="submit">Save</button>
      <a class="btn ghost" href="{{ route('admin.courses') }}">Cancel</a>
    </div>
  </form>
@endsection
