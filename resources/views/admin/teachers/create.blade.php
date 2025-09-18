@extends('layouts.admin')
@section('title','Add Teacher')

@section('content')
  <h2 class="mb-3">Add Teacher</h2>

  @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.teachers.add') }}" class="card p-3">
    @csrf
    <label class="mb-2">Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required class="mb-3">

    <label class="mb-2">Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required class="mb-3">

    <label class="mb-2">Password</label>
    <input type="password" name="password" required class="mb-4">

    <div style="display:flex; gap:8px">
      <button class="btn dark" type="submit">Save</button>
      <a class="btn ghost" href="{{ route('admin.teachers') }}">Cancel</a>
    </div>
  </form>
@endsection
