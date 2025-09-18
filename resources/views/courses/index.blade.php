@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド: コース一覧 --}}
        <div class="col-md-3 border-end bg-white" style="min-height:100vh;">
            <h3 class="fw-bold mb-3">Courses</h3>

            {{-- タブ切替 --}}
            <ul class="nav nav-pills mb-3">
                <li class="nav-item"><a class="nav-link active" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Active</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Completed</a></li>
            </ul>

            {{-- 言語フィルター --}}
            <div class="mb-3">
                <button class="btn btn-outline-secondary btn-sm me-2">English</button>
                <button class="btn btn-outline-secondary btn-sm">IT</button>
            </div>

            {{-- コース一覧 --}}
@foreach($courses as $c)
<a href="{{ route('courses.index', ['course' => $c->id]) }}" 
   class="text-decoration-none text-dark">
    <div class="d-flex align-items-center mb-3 p-2 border rounded shadow-sm 
                {{ isset($selectedCourse) && $selectedCourse->id === $c->id ? 'bg-light border-primary' : '' }}">
        <img src="{{ $c->image ?? 'https://via.placeholder.com/60x60' }}" 
             alt="{{ $c->title }}" 
             class="rounded me-2" style="width:60px;height:60px;object-fit:cover;">
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-bold">{{ $c->title }}</h6>
            @if(in_array($c->id, $enrolledCourseIds ?? []))
                @php $rate = $c->completionRate(auth()->id()); @endphp
                <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                </div>
                <small class="text-muted">{{ $rate }}% Finish</small>
            @else
                <small class="text-muted">{{ $c->language ?? 'English' }}</small>
            @endif
        </div>
    </div>
</a>
@endforeach


        {{-- 右サイド: 選択したコース --}}
        <div class="col-md-9 ps-4">
            @isset($selectedCourse)
                @if(in_array($selectedCourse->id, $enrolledCourseIds ?? []))
                    {{-- 受講中（進捗UI） --}}
                    @include('courses.partials.enrolled', ['course' => $selectedCourse])
                @else
                    {{-- 未受講（コース紹介UI） --}}
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
