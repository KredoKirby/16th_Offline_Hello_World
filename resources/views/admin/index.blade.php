@extends('layouts.admin')
@section('title','Admin Dashboard')

@section('content')

  {{-- 修正済み --}}
  
  <h1 class="mb-4">Dashboard</h1>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card-lite">
        <h5 class="mb-2">Students ({{ $studentCount }})</h5>
        <ul class="mb-2">
          @foreach($latestStudents as $s)
            <li>{{ $s['name'] }}</li>
          @endforeach
        </ul>
        <a href="{{ route('admin.students') }}" class="link-primary">View More</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-lite">
        <h5 class="mb-2">Teachers ({{ $teacherCount }})</h5>
        <ul class="mb-2">
          @foreach($latestTeachers as $t)
            <li>{{ $t['name'] }}</li>
          @endforeach
        </ul>
        <a href="{{ route('admin.teachers') }}" class="link-primary">View More</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card-lite">
        <h5 class="mb-2">Courses ({{ $courseCount }})</h5>
        <ul class="mb-2">
          @foreach($latestCourses as $c)
            <li>{{ $c['name'] }}</li>
          @endforeach
        </ul>
        <a href="{{ route('admin.courses') }}" class="link-primary">View More</a>
      </div>
    </div>

    <div class="col-12">
      <div class="card-lite">
        <h5 class="mb-3">Forums ({{ $forumCount }})</h5>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr><th>Question</th><th>Course</th><th>Username</th></tr>
            </thead>
            <tbody>
              @foreach($latestForums as $f)
                <tr>
                  <td>{{ $f['question'] }}</td>
                  <td>{{ $f['course'] }}</td>
                  <td>{{ $f['username'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <a href="{{ route('admin.forums') }}" class="link-primary">View More</a>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('admin.index') }}">Home</a> |
    <a href="{{ route('admin.students') }}">Students</a> |
    <a href="{{ route('admin.teachers') }}">Teachers</a> |
    <a href="{{ route('admin.courses') }}">Courses</a> |
    <a href="{{ route('admin.forums') }}">Forum</a>
  </div>
@endsection
