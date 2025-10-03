@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('content')
<div class="dashboard container-fluid p-4">

    {{-- 検索バー --}}
    <div class="mb-3 d-flex align-items-center video-search-bar">
        <div class="input-group dashboard-search">
            <span class="input-group-text bg-white">
                <i class="fa-solid fa-magnifying-glass"></i>
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
                        <div class="dashboard-status-card dashboard-status-enrolled">
                            <p class="mb-1">Courses enrolled</p>
                            <h2>{{ $myCourses->count() }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-status-card dashboard-status-completed">
                            <p class="mb-1">Courses completed</p>
                            <h2>{{ $completedCourses }}</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-status-card dashboard-status-hours">
                            <p class="mb-1">Hours Learned</p>
                            <h2>{{ $hoursLearned }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- My Courses --}}
            <div class="mb-4 dashboard-courses">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">My courses</h5>
                    {{-- Active / Completed / All --}}
                    <div class="video-status-tabs">
                         <button class="tab-btn" data-target="all">All</button>
                        <button class="tab-btn active" data-target="active">Active</button>
                        <button class="tab-btn" data-target="completed">Completed</button>
                       
                    </div>
                </div>

                {{-- Active Courses --}}
                <div id="active-courses" class="course-list">
                    @foreach($myCourses->where('status', 'active') as $course)
                        <a href="{{ route('selflearning.show', $course->id) }}" class="text-decoration-none text-dark">
                            <div class="dashboard-course-card d-flex flex-row align-items-center">
                                <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}" 
                                     alt="course" class="me-3 rounded dashboard-course-img">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $course->title }}</h6>
                                    <div class="d-flex align-items-center">
                                        @php $rate = $course->completionRate(Auth::id()); @endphp
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

                {{-- Completed Courses --}}
                <div id="completed-courses" class="course-list d-none">
                    @foreach($myCourses->where('status', 'completed') as $course)
                        <a href="{{ route('selflearning.show', $course->id) }}" class="text-decoration-none text-dark">
                            <div class="dashboard-course-card d-flex flex-row align-items-center">
                                <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}" 
                                     alt="course" class="me-3 rounded dashboard-course-img">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $course->title }}</h6>
                                    <div class="d-flex align-items-center">
                                        @php $rate = $course->completionRate(Auth::id()); @endphp
                                        <small class="me-2">{{ $rate }}% Finish</small>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-success" style="width: {{ $rate }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- All Courses --}}
                <div id="all-courses" class="course-list d-none">
                    @foreach($myCourses as $course)
                        <a href="{{ route('selflearning.show', $course->id) }}" class="text-decoration-none text-dark">
                            <div class="dashboard-course-card d-flex flex-row align-items-center">
                                <img src="{{ asset('images/courses/' . ($course->image ?? 'sample.jpg')) }}" 
                                     alt="course" class="me-3 rounded dashboard-course-img">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $course->title }}</h6>
                                    <div class="d-flex align-items-center">
                                        @php $rate = $course->completionRate(Auth::id()); @endphp
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
        </div>

        {{-- 右サイド --}}
        <div class="col-md-3">

            {{-- Schedule --}}
            <div class="dashboard-side-card mb-4">
                <h6 class="fw-bold">Schedule</h6>
                <img src="{{ asset('images/calendar.jpg') }}" class="img-fluid rounded" alt="calendar">
            </div>

            {{-- Recommended --}}
            <div class="dashboard-side-card">
                <h6 class="fw-bold">Recommended courses</h6>
                @foreach($recommendedCourses as $rec)
                <div class="dashboard-recommend-item d-flex flex-row align-items-center">
                    <img src="{{ asset('images/courses/' . ($rec->image ?? 'php.jpg')) }}" 
                         alt="course" class="me-2 rounded dashboard-recommend-img">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.video-status-tabs .tab-btn');
    const lists = {
        active: document.getElementById('active-courses'),
        completed: document.getElementById('completed-courses'),
        all: document.getElementById('all-courses'),
    };

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            // ボタンのactive切替
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // リスト切替
            Object.values(lists).forEach(list => list.classList.add('d-none'));
            lists[this.dataset.target].classList.remove('d-none');
        });
    });
});
</script>
@endpush
