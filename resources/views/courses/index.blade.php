@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド --}}
        <div class="col-md-3 border-end bg-white" style="min-height:100vh;">
            <h3 class="fw-bold mb-3">Courses</h3>

            {{-- タブ --}}
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == '' ? 'active' : '' }}" 
                       href="{{ route('courses.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" 
                       href="{{ route('courses.index', ['status' => 'active']) }}">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
                       href="{{ route('courses.index', ['status' => 'completed']) }}">Completed</a>
                </li>
            </ul>

            {{-- 言語フィルタ --}}
            <div class="mb-3">
                <a href="{{ route('courses.index', array_merge(request()->query(), ['lang' => 'English'])) }}" 
                   class="btn btn-outline-dark btn-sm me-1 {{ request('lang')=='English'?'active':'' }}">English</a>
                <a href="{{ route('courses.index', array_merge(request()->query(), ['lang' => 'IT'])) }}" 
                   class="btn btn-outline-dark btn-sm {{ request('lang')=='IT'?'active':'' }}">IT</a>
            </div>

            {{-- コース一覧 --}}
            @foreach($courses as $c)
                {{-- status/lang フィルタを blade 側で反映 --}}
                @php
                    $isEnrolled = in_array($c->id, $enrolledCourseIds ?? []);
                    $rate = $isEnrolled ? $c->completionRate(auth()->id()) : 0;

                    // status フィルタ
                    if(request('status')=='active' && (!$isEnrolled || $rate==100)) continue;
                    if(request('status')=='completed' && (!$isEnrolled || $rate<100)) continue;

                    // lang フィルタ
                    if(request('lang') && request('lang')!=$c->language) continue;
                @endphp

                <a href="{{ route('courses.show', $c->id) }}" 
                   class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center mb-3 p-2 border rounded shadow-sm 
                                {{ isset($selectedCourse) && $selectedCourse->id === $c->id ? 'bg-light border-primary' : '' }}">
                        <img src="{{ $c->image ?? 'https://via.placeholder.com/60x60' }}" 
                             alt="{{ $c->title }}" 
                             class="rounded me-2" style="width:60px;height:60px;object-fit:cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $c->title }}</h6>
                            @if($isEnrolled)
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                                </div>
                                <small class="text-muted">{{ $rate }}% Finish</small>
                            @else
                                <small class="badge bg-light text-dark border">{{ $c->language ?? 'English' }}</small>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- 右サイド --}}
        <div class="col-md-9 ps-4">
            @isset($selectedCourse)
                @if(in_array($selectedCourse->id, $enrolledCourseIds ?? []))
                    {{-- 受講中 --}}
                    @include('courses.partials.enrolled', ['course' => $selectedCourse])
                @else
                    {{-- 未受講 --}}
                    @include('courses.partials.preview', ['course' => $selectedCourse])
                @endif
            @else
                <div class="text-center text-muted mt-5">
                    <p>Please select a course from the left panel →</p>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
