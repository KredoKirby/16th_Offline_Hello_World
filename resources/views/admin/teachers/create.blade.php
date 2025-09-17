@extends('admin.partials.layout')

@section('title','Add a teacher')
@section('content')
  <h2 style="margin:0 0 12px;">Add a teacher</h2>

  <div class="card" style="padding:18px; max-width:520px">
    @if ($errors->any())
      <div style="background:#fee2e2; padding:10px; border-radius:6px; margin-bottom:12px">
        <ul style="margin:0; padding-left:16px">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.teachers.add') }}">
      @csrf
      <div style="margin-bottom:10px">
        <label>Name<br>
          <input name="name" value="{{ old('name') }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
        </label>
      </div>
      <div style="margin-bottom:10px">
        <label>Email<br>
          <input name="email" type="email" value="{{ old('email') }}" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
        </label>
      </div>
      <div style="margin-bottom:14px">
        <label>Password<br>
          <input name="password" type="password" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
        </label>
      </div>
      <div style="display:flex; gap:8px">
        <button class="btn dark" type="submit">+ Add</button>
        <a class="btn ghost" href="{{ route('admin.teachers') }}">Cancel</a>
      </div>
    </form>
  </div>
@endsection
