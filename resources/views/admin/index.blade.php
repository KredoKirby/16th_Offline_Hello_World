@extends('layouts.admin')
@section('title', 'Hello World')

@section('content')
    {{-- 上段：3カード --}}
    <div class="row g-3">
        {{-- Students --}}
        <div class="col-md-4">
            <div class="card shadow rounded-1 h-100 border-0" style="background-color:#FFF5F5;">
                <div class="card-body">
                    <h4 class="card-title fw-bold mb-3">{{ $studentsCount }} Students</h4>
                    <ul class="list-group list-group-flush">
                        @forelse(collect($latestStudents ?? [])->take(5) as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-semibold border-0"
                                style="background-color:#FFF5F5;">
                                {{ $s['name'] ?? 'User Name' }}
                                <span class="fw-bold">…</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center border-0" style="background-color:#FFF5F5;">
                                No students yet
                            </li>
                        @endforelse
                    </ul>
                    <a href="{{ route('admin.students.index') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                </div>
            </div>
        </div>

        {{-- Teachers --}}
        <div class="col-md-4">
            <div class="card shadow rounded-1 h-100 border-0" style="background-color:#F0FFF7;">
                <div class="card-body">
                    <h4 class="card-title fw-bold mb-3">{{ $teachersCount }} Teachers</h4>
                    <ul class="list-group list-group-flush">
                        @forelse(collect($latestTeachers ?? [])->take(5) as $t)
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-semibold border-0"
                                style="background-color:#F0FFF7;">
                                {{ $t['name'] ?? 'User Name' }}
                                <span class="fw-bold">…</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center border-0" style="background-color:#F0FFF7;">
                                No teachers yet
                            </li>
                        @endforelse
                    </ul>
                    <a href="{{ route('admin.teachers.index') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                </div>
            </div>
        </div>

        {{-- Courses --}}
        <div class="col-md-4">
            <div class="card shadow rounded-1 h-100 border-0" style="background-color:#ECF0FF;">
                <div class="card-body">
                    <h4 class="card-title fw-bold mb-3">{{ $coursesCount }} Courses</h4>
                    <ul class="list-group list-group-flush">
                        @forelse(collect($latestCourses ?? [])->take(5) as $c)
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-semibold border-0"
                                style="background-color:#ECF0FF;">
                                {{ $c['name'] ?? 'Course Name' }}
                                <span class="fw-bold">…</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center border-0" style="background-color:#ECF0FF;">
                                No courses yet
                            </li>
                        @endforelse
                    </ul>
                    <a href="{{ route('admin.courses.index') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                </div>
            </div>
        </div>
    </div>

    {{-- 下段：Forums --}}
    <div class="col-12 mt-4">
        <div class="card shadow rounded-1">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-3">{{ $forumsCount }} Forums</h4>

                {{-- ヘッダー行 --}}
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-12 col-lg-4">
                        <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-bold fs-6">Question</div>
                    </div>
                    <div class="col-6 col-lg-4">
                        <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-bold fs-6">Course</div>
                    </div>
                    <div class="col-5 col-lg-3">
                        <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-bold fs-6">Username</div>
                    </div>
                    <div class="col-1 d-none d-lg-flex justify-content-center align-items-center fw-bold fs-5">…</div>
                </div>

                {{-- データ行 --}}
                @forelse(collect($latestForums ?? [])->take(4) as $f)
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-12 col-lg-4">
                            <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                {{ $f['question'] ?? 'Question' }}
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                {{ $f['course'] ?? 'Course' }}
                            </div>
                        </div>
                        <div class="col-5 col-lg-3">
                            <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                {{ $f['username'] ?? 'Username' }}
                            </div>
                        </div>
                        <div class="col-1 d-none d-lg-flex justify-content-center align-items-center fw-bold fs-5">…</div>

                    </div>
                @empty
                    <div class="text-center text-muted py-3">No forums yet</div>
                @endforelse

                <a href="{{ route('admin.forums.index') }}" class="d-block text-center mt-2 fw-bold">View More</a>
            </div>
        </div>
    </div>
@endsection
