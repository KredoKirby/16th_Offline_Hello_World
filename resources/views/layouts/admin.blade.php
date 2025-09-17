<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- どちらか一つにする： --}}
  {{-- A) すぐ反映したいなら Bootstrap CDN を使用（npm不要） --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- B) Breeze/Vite を使っているならこちら（npm run dev が必要） --}}
  {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}

  <title>@yield('title','Admin')</title>

  <style>
    body{ background:#fde1e1; }
    .admin-wrap{ max-width:1200px; margin:24px auto; }
    .sidebar{ width:220px; background:#9bd1d1; border-radius:12px; padding:20px; }
    .sidebar a{ color:#083b4c; font-weight:700; text-decoration:none; display:block; padding:8px 0; }
    .content{ background:#fff; border-radius:12px; padding:24px; box-shadow:0 2px 10px rgba(0,0,0,.05); }
    .card-lite{ border:1px solid #eee; border-radius:10px; padding:16px; }
  </style>
</head>
<body>
<div class="admin-wrap container-fluid">
  <div class="row g-3">
    <aside class="col-12 col-md-3">
      <div class="sidebar">
        <div class="mb-3 fw-bold">Hello World</div>
        <a href="{{ route('admin.dashboard') }}">Home</a>
        <a href="{{ route('admin.students.index') }}">Students</a>
        <a href="{{ route('admin.teachers.index') }}">Teachers</a>
        <a href="{{ route('admin.courses.index') }}">Courses</a>
        <a href="#">Self-learning</a>
        <a href="#">Forum</a>
        <hr>
        <div class="small">Username</div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-link p-0 text-danger">Logout</button>
        </form>
      </div>
    </aside>

    <main class="col-12 col-md-9">
      <div class="content">
        @yield('content')
      </div>
    </main>
  </div>
</div>

{{-- CDN を使う場合のみ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
