@extends('layouts.app')
@section('title', 'Hello World')

@section('content')
    <div class="row g-0 h-100">

        {{-- ===== 右メイン ===== --}}
        <main class="col-12 col-md-9 col-lg-10 p-3 vh-100 overflow-auto">

            {{-- 上段：3カード --}}
            <div class="row g-3">
                {{-- Students --}}
                <div class="col-md-4">
                    <div class="card shadow-sm h-100" style="background-color: #FFF5F5;">
                        <div class="card-body">
                            <h4 class="card-title fw-bold mb-3">{{ count($students ?? []) }} Students</h4>
                            <ul class="list-group list-group-flush">
                                @foreach (collect($students ?? [])->take(5) as $s)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                                        {{ $s['name'] ?? 'User Name' }}
                                        <span class="fw-bold">…</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('admin.students') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                        </div>
                    </div>
                </div>

                {{-- Teachers --}}
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-success" style="background-color: #F0FFF7;">
                        <div class="card-body">
                            <h4 class="card-title fw-bold mb-3">{{ count($teachers ?? []) }} Teachers</h4>
                            <ul class="list-group list-group-flush">
                                @foreach (collect($teachers ?? [])->take(5) as $t)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                                        {{ $t['name'] ?? 'User Name' }}
                                        <span class="fw-bold">…</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('admin.teachers') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                        </div>
                    </div>
                </div>

                {{-- Courses --}}
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 border-primary" style="background-color: #ECF0FF;">
                        <div class="card-body">
                            <h4 class="card-title fw-bold mb-3">{{ count($courses ?? []) }} Courses</h4>
                            <ul class="list-group list-group-flush">
                                @foreach (collect($courses ?? [])->take(5) as $c)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                                        {{ $c['name'] ?? 'Course Name' }}
                                        <span class="fw-bold">…</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('admin.courses') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 下段：Forums --}}
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title fw-bold mb-3">{{ count($forums ?? []) }} Forums</h4>

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
                            <div class="col-auto d-flex justify-content-end align-items-center">
                                <span class="fw-bold fs-5">…</span>
                            </div>

                        </div>

                        {{-- データ行 --}}
                        @forelse(collect($forums ?? [])->take(4) as $f)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-12 col-lg-4">
                                    <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                        {{ $f['question'] ?? 'Question' }}</div>
                                </div>
                                <div class="col-6 col-lg-4">
                                    <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                        {{ $f['course'] ?? 'Course' }}</div>
                                </div>
                                <div class="col-5 col-lg-3">
                                    <div class="bg-secondary-subtle rounded-3 px-3 py-2 fw-semibold">
                                        {{ $f['username'] ?? 'Username' }}</div>
                                </div>
                                <div class="col-1 d-none d-lg-block text-end fw-bold fs-5">…</div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">No forums yet</div>
                        @endforelse

                        <a href="{{ route('admin.forums') }}" class="d-block text-center mt-2 fw-bold">View More</a>
                    </div>
                </div>
            </div>

        </main>
    </div>
@endsection
