@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="container">
    <h1 class="mb-4">Courses</h1>

    @if($courses->isEmpty())
        <p>No courses available.</p>
    @else
        <ul class="list-group">
            @foreach($courses as $course)
                <li class="list-group-item">
                    <strong>{{ $course->title }}</strong><br>
                    <small>{{ $course->description }}</small>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
