@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container py-4 w-50">

        {{-- Profile card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    {{-- Photo + Upload --}}
                    <div class="col-md-4">
                        <div class="text-center">
                            <div style="width:200px;height:200px;object-fit:cover;">
                                <img src="#" alt="Profile" class="img-fluid rounded-4 border">
                            </div>
                            <button type="button" class="btn btn-light border shadow-sm mt-3">
                                Upload Photo
                            </button>
                        </div>
                    </div>

                    {{-- Name / Email / About --}}
                    <div class="col-md-8">
                        <h3 class="h5 fw-bold mb-3">Shinya Nakaguchi</h3>
                        <dl class="row mb-4">
                            <dt class="col-5 col-sm-2">Email</dt>
                            <dd class="col-7 col-sm-10">Test</dd>
                            <dt class="col-5 col-sm-2">About</dt>
                            <dd class="col-7 col-sm-10">Test</dd>
                        </dl>
                        {{-- Edit button --}}
                        <div>
                            <a href="#" class="btn btn-info fw-semibold px-4 shadow-sm">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- My courses --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="h6 fw-bold mb-4">My courses</h4>

                <div class="vstack gap-4">
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="row g-3 align-items-center">
                            {{-- Thumb --}}
                            <div class="col-auto">
                                <img src="{{ asset('images/course.jpg') }}" alt="Course {{ $i }}" class="rounded"
                                    style="width:72px;height:72px;object-fit:cover;">
                            </div>

                            {{-- Title --}}
                            <div class="col">
                                <div class="fw-semibold">Basic English {{ $i }}</div>
                            </div>

                            {{-- Progress --}}
                            <div class="col-12 col-md-5">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">&nbsp;</span>
                                    <span class="small fw-semibold">90% Finish</span>
                                </div>
                                <div class="progress" style="height:10px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width:90%;"
                                        aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        @if ($i < 2)
                            <hr class="my-0">
                        @endif
                    @endfor
                </div>
            </div>
        </div>

    </section>
@endsection
