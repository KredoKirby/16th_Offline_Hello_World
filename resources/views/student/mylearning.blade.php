@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="container py-4">
        <!-- Tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#learning"
                    type="button">Learning</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed"
                    type="button">Completed</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#wishlist"
                    type="button">Wishlist</button></li>
        </ul>

        <div class="tab-content pt-3">
            {{-- Learning --}}
            <div class="tab-pane fade show active" id="learning">
                <div class="row g-4">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div
                                    class="ratio ratio-16x9 bg-light rounded-top d-flex align-items-center justify-content-center">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-1">Course Name {{ $i }}</h6>
                                    <p class="card-text text-muted mb-3">Description {{ $i }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="text-decoration-none d-block text-center stretched-link">View
                                        More</a>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Completed --}}
            <div class="tab-pane fade" id="completed">
                <div class="row g-4">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div
                                    class="ratio ratio-16x9 bg-light rounded-top d-flex align-items-center justify-content-center">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-1">Course Name {{ $i }}</h6>
                                    <p class="card-text text-muted mb-3">Description {{ $i }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="text-decoration-none d-block text-center stretched-link">View
                                        More</a>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Wishlist --}}
            <div class="tab-pane fade" id="wishlist">
                <div class="row g-4">
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <div
                                    class="ratio ratio-16x9 bg-light rounded-top d-flex align-items-center justify-content-center">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-1">Course Name {{ $i }}</h6>
                                    <p class="card-text text-muted mb-3">Description {{ $i }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="#" class="text-decoration-none d-block text-center stretched-link">View
                                        More</a>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

@endsection
