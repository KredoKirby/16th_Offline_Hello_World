@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container">
    <div class="row">
        {{-- left side --}}
        <div class="col-md-3">
            <h2 class="mb-3">Courses</h2>

            <div class="mb-3">
                <button class="btn btn-outline-dark btn-sm">All</button>
                <button class="btn btn-dark btn-sm">Active</button>
                <button class="btn btn-outline-dark btn-sm">Completed</button>
            </div>

            {{-- コース一覧 --}}
            @foreach(\App\Models\Course::all() as $c)
                <div class="card mb-3">
                    <img src="{{ $c->image ?? 'https://via.placeholder.com/150' }}" 
                         class="card-img-top" alt="{{ $c->title }}">
                    <div class="card-body">
                        <h6 class="card-title">{{ $c->title }}</h6>
                        <p class="small text-muted">{{ ucfirst($c->language) }}</p>

                        {{-- 進捗バー --}}
                        <div class="progress mb-1">
                            <div class="progress-bar" role="progressbar" style="width:30%"></div>
                        </div>
                        <small>30% Finish</small>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- right side--}}
        <div class="col-md-9">
            {{-- コース画像 --}}
            <img src="{{ $course->image ?? 'https://via.placeholder.com/800x200' }}" 
                 class="img-fluid mb-4 rounded">

            <h2>{{ $course->title }}</h2>
            <p>{{ $course->description }}</p>

            {{-- レッスン一覧 --}}
            <div class="accordion" id="lessonsAccordion">
                @foreach($course->lessons as $index => $lesson)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $lesson->id }}">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $lesson->id }}">
                                {{ $index+1 }}. {{ $lesson->title }}
                            </button>
                        </h2>
                        <div id="collapse{{ $lesson->id }}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
