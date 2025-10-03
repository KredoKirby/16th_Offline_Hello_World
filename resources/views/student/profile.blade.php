@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container py-4 layout-narrow">

        {{-- Profile Card – Minimal --}}
        <div class="card ui-card mb-5">
            <div class="card-body p-4 p-md-5">
                <div class="row g-5 align-items-center">

                    {{-- Photo + Upload --}}
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="avatar mx-auto">
                                <img src="#" alt="Profile photo" class="avatar-img">
                            </div>
                            <form action="#" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <input id="photo" name="photo" type="file" class="d-none" accept="image/*">
                                <label for="photo" class="btn btn-ghost px-4">Change Photo</label>
                            </form>
                        </div>
                    </div>

                    {{-- Name / Email / About --}}
                    <div class="col-md-8">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <h1 class="title mb-1">{{ $user->name }}</h1>
                                <div class="meta small">{{ $user->email }}</div>
                            </div>
                            <div class="d-none d-md-block">
                                <a href="#" class="btn btn-primary-solid px-4">Edit</a>
                            </div>
                        </div>

                        <hr class="rule my-3">

                        <div>
                            <div class="section-label mb-2">About</div>
                            <p class="body-text mb-0">{{ $user->about ?? '—' }}</p>
                        </div>

                        <div class="d-grid d-md-none mt-3">
                            <a href="#" class="btn btn-primary-solid">Edit</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- My courses --}}
        <div class="card ui-card">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="subtitle mb-0">My Courses</h2>
                    <a href="#" class="link-quiet small">View all</a>
                </div>

                <div class="vstack gap-0 list bordered">
                    @for ($i = 1; $i <= 2; $i++)
                        <a href="#" class="list-item">
                            <div class="row g-3 align-items-center">

                                {{-- Thumb --}}
                                <div class="col-auto">
                                    <div class="thumb">
                                        <img src="{{ asset('images/course.jpg') }}" alt="Course {{ $i }}"
                                            class="thumb-img">
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div class="col">
                                    <div class="fw-semibold item-title">Basic English {{ $i }}</div>
                                    <div class="item-meta small">2 lessons left</div>
                                </div>

                                {{-- Progress --}}
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small text-body-secondary">Progress</span>
                                        <span class="small fw-semibold">90%</span>
                                    </div>
                                    <div class="progress-soft">
                                        <div class="progress-soft-bar" style="--val:90%;"></div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    @endfor
                </div>
            </div>
        </div>

    </section>
@endsection
