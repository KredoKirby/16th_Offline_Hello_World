@extends('layouts.app')
@section('title', 'Add Course')

@section('content')
  <h2 class="mb-3">Add Course</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.courses.store') }}" class="card p-3" enctype="multipart/form-data">
    @csrf

    {{-- Title --}}
    <label class="mb-2">Title</label>
    <input type="text" name="title" value="{{ old('title') }}" required class="form-control mb-4">

    {{-- Price --}}
    <label class="mb-2">Price</label>
    <input type="number" step="0.01" name="price" value="{{ old('price', 5000) }}" class="form-control mb-4">

    {{-- Description --}}
    <label class="mb-2">Description</label>
    <textarea name="description" rows="3" class="form-control mb-4">{{ old('description') }}</textarea>

    {{-- Category --}}
    <label class="mb-2">Category</label>
    <input type="text" name="category" value="{{ old('category') }}" class="form-control mb-4">

    {{-- Language --}}
    <label class="mb-2">Language</label>
    <select name="language" class="form-select mb-4">
      <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
      <option value="jp" {{ old('language') == 'jp' ? 'selected' : '' }}>Japanese</option>
    </select>

    {{-- Level --}}
    <label class="mb-2">Level</label>
    <select name="level" class="form-select mb-4">
      <option value="basic" {{ old('level') == 'basic' ? 'selected' : '' }}>Basic</option>
      <option value="advance" {{ old('level') == 'advance' ? 'selected' : '' }}>Advance</option>
      <option value="expert" {{ old('level') == 'expert' ? 'selected' : '' }}>Expert</option>
    </select>

    {{-- Image Upload --}}
    <label class="mb-2">Course Image</label>
    <input type="file" name="image_file" id="image_file" accept="image/*" class="form-control mb-3">

    {{-- プレビュー --}}
    <img id="preview" src="{{ old('image') }}" alt="Preview" style="max-width: 200px; display:none; border-radius:8px; margin-bottom:1rem;">

    {{-- hidden base64 --}}
    <input type="hidden" name="image" id="image">

    <div style="display:flex; gap:8px">
      <button class="btn btn-dark" type="submit">Save</button>
      <a class="btn btn-outline-secondary" href="{{ route('admin.courses') }}">Cancel</a>
    </div>
  </form>

  <script>
    // ファイル選択時にBase64に変換してhiddenにセット
    document.getElementById('image_file').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(event) {
        document.getElementById('image').value = event.target.result;
        const preview = document.getElementById('preview');
        preview.src = event.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    });
  </script>
@endsection
