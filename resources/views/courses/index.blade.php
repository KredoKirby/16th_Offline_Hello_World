@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- 左サイド --}}
        <div class="col-md-3 border-end bg-white" style="min-height:100vh;">
            <h3 class="fw-bold mb-3">
                <a href="{{ route('courses.index') }}" class="text-decoration-none text-dark">Courses</a>
            </h3>

            @include('courses.partials.left-sidebar', ['courses'=>$courses])
        </div>

        {{-- 右サイド（空） --}}
        <div class="col-md-9 ps-4">
            <div class="text-center text-muted mt-5">
                <p>Please select a course from the left panel →</p>
            </div>
        </div>

    </div>
</div>
@endsection