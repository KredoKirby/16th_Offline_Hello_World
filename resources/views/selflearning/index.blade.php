@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">

    {{-- 検索バー --}}
    <div class="mb-3 d-flex align-items-center">
        <div class="input-group" style="width: 250px;">
            <span class="input-group-text bg-white">
                <i class="fa-solid fa-magnifying-glass""></i>
            </span>
            <input type="text" class="form-control" placeholder="search">
        </div>
    </div>

    <div class="row">
        {{-- 左サイド --}}
        <div class="col-md-9">

            {{-- Status --}}
            <div class="mb-4">
                <h5 class="fw-bold">Status</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card text-center p-3" style="background:#d4f8d4;">
                            <p class="mb-1 fw-bold">Courses enrolled</p>
                            <h2 class="fw-bold">{{ $myCourses->count() }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3" style="background:#ffd6d6;">
                            <p class="mb-1 fw-bold">Courses completed</p>
                            <h2 class="fw-bold">{{ $completedCourses }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3" style="background:#fff7cc;">
                            <p class="mb-1 fw-bold">Hours Learned</p>
                            <h2 class="fw-bold">{{ $hoursLearned }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- My Courses --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">My courses</h5>

                    {{-- タブ --}}
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Active</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Completed</a>
                        </li>
                    </ul>
                </div>

                {{-- 動的にコースをループ --}}
                @foreach($myCourses as $course)
                    @php $rate = $course->completionRate(Auth::id()); @endphp
                    <a href="{{ route('selflearning.show', $course->id) }}" class="text-decoration-none text-dark">

                      <div class="card mb-3 p-3 d-flex flex-row align-items-center">
                          <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}" 
                              alt="course" class="me-3 rounded" 
                              style="width:80px; height:60px; object-fit:cover;">
                          <div class="flex-grow-1">
                              <h6 class="mb-1">{{ $course->title }}</h6>
                              <div class="d-flex align-items-center">
                                  <small class="me-2">{{ $rate }}% Finish</small>
                                  <div class="progress w-100">
                                      <div class="progress-bar {{ $rate > 0 ? 'bg-info' : 'bg-secondary' }}" 
                                          style="width: {{ $rate }}%">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </a>

                @endforeach
            </div>

        </div>

        {{-- 右サイド --}}
        <div class="col-md-3">

            {{-- Schedule --}}
            <div class="border rounded p-3 mb-4">
                <h6 class="fw-bold">Schedule</h6>
                <img src="{{ asset('images/calendar.jpg') }}" class="img-fluid rounded" alt="calendar">
            </div>

            {{-- Recommended --}}
            <div class="border rounded p-3">
                <h6 class="fw-bold">Recommended courses</h6>
                @foreach($recommendedCourses as $rec)
                <div class="card mb-2 d-flex flex-row align-items-center p-2">
                    <img src="{{ asset('images/courses/' . ($rec->image ?? 'php.jpg')) }}" 
                         alt="course" class="me-2 rounded" 
                         style="width:60px; height:40px; object-fit:cover;">
                    <div>
                        <h6 class="mb-0 small fw-bold">{{ $rec->title }}</h6>
                        <small class="text-muted">{{ Str::limit($rec->description, 30) }}</small>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
