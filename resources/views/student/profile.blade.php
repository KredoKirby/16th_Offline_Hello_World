@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @php
        /** @var \App\Models\User|null $viewer */
        $viewer = auth()->user();
        $isAdmin = (int) ($viewer->role_id ?? 0) === 1; // 1 = admin
        $isSelf = (int) ($viewer->id ?? 0) === (int) ($user->id ?? -1);
        $canSeeAll = $isAdmin || $isSelf; // 管理者 or 本人だけ編集系を表示
    @endphp

    <section class="container py-4 layout-narrow">

        {{-- Profile Card --}}
        <div class="card ui-card mb-5">
            <div class="card-body p-4 p-md-5">
                <div class="row g-5 align-items-center">

                    {{-- Photo + Change Photo --}}
<div class="col-md-4">
    <div class="text-center">

        {{-- Avatar --}}
        <div class="avatar mx-auto">
            <img
                alt="Profile photo"
                class="avatar-img rounded-circle"
                src="{{ $user->avatar_path
                        ? asset('storage/' . $user->avatar_path)
                        : asset('images/default-avatar.png') }}">
        </div>

        {{-- Change Photo（本人 or 管理者のみ） --}}
        @if ($canSeeAll)
            <form action="{{ route('students.profile.photo.update', ['user' => $user->id]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="mt-3">
                @csrf
                @method('PUT')

                <input id="photo" name="photo" type="file"
                       class="d-none"
                       accept="image/*"
                       onchange="this.form.submit()">

                <label for="photo" class="btn btn-ghost px-4">
                    Change Photo
                </label>
            </form>
        @endif

    </div>
</div>

                    {{-- Name / Email / About --}}
                    <div class="col-md-8">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <h1 class="title mb-1">{{ $user->name }}</h1>

                                @if ($canSeeAll)
                                    <div class="meta small">{{ $user->email }}</div>
                                @endif
                            </div>

                            {{-- Edit（PC） --}}
                            @if ($canSeeAll)
                                <div class="d-none d-md-block">
                                    <button type="button" class="btn btn-primary-solid px-4" data-bs-toggle="modal"
                                        data-bs-target="#editProfileModal">
                                        Edit
                                    </button>
                                </div>
                            @endif
                        </div>

                        <hr class="rule my-3">

                        <div>
                            <div class="section-label mb-2">About</div>
                            <p class="body-text mb-0">
                                {{ $user->about ? $user->about : '—' }}
                            </p>
                        </div>

                        {{-- Edit（SP） --}}
                        @if ($canSeeAll)
                            <div class="d-grid d-md-none mt-3">
                                <button type="button" class="btn btn-primary-solid" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                    Edit
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- My Learning（デザイン踏襲・ダミー。実データ差し替え前提） --}}
        <div class="card ui-card">
            <div class="card-body p-3 p-md-4">

                {{-- Header --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h2 class="subtitle mb-0">My Learning</h2>
                        <div class="text-muted small">
                            Your current courses, completed history & wishlist.
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <ul class="nav nav-tabs mylearning-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#learning" type="button">
                            Learning
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                            Completed
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wishlist" type="button">
                            Wishlist
                        </button>
                    </li>
                </ul>

                <div class="tab-content pt-3">

                    {{-- Learning --}}
                    <div class="tab-pane fade show active" id="learning">
                        <div class="row g-3 g-md-4">
                            @for ($i = 1; $i <= 3; $i++)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="learning-card card h-100 border-0">
                                        <div class="learning-thumb ratio ratio-16x9">
                                            <div class="learning-thumb-inner">
                                                <span class="badge rounded-pill learning-badge">
                                                    In progress
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title fw-semibold mb-1">
                                                Course Name {{ $i }}
                                            </h6>
                                            <p class="card-text text-muted small mb-2">
                                                Short description for course {{ $i }}.
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="text-muted xsmall">Progress</span>
                                                <span class="xsmall fw-semibold">45%</span>
                                            </div>
                                            <div class="progress-soft">
                                                <div class="progress-soft-bar" style="--val:45%;"></div>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2 mt-2 xsmall text-muted">
                                                <span><i class="fa-regular fa-circle-play me-1"></i>8 / 16 lessons</span>
                                                <span><i class="fa-regular fa-clock me-1"></i>Next: Nov 10</span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                            <a href="#" class="btn btn-sm btn-outline-primary w-100">
                                                Continue
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Completed --}}
                    <div class="tab-pane fade" id="completed">
                        <div class="row g-3 g-md-4">
                            @for ($i = 1; $i <= 2; $i++)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="learning-card card h-100 border-0">
                                        <div class="learning-thumb ratio ratio-16x9">
                                            <div class="learning-thumb-inner">
                                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis">
                                                    <i class="fa-regular fa-circle-check me-1"></i>
                                                    Completed
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title fw-semibold mb-1">
                                                Course Name {{ $i }}
                                            </h6>
                                            <p class="card-text text-muted small mb-2">
                                                Completed course description {{ $i }}.
                                            </p>
                                            <div class="xsmall text-muted">
                                                Finished on: 2025-10-{{ 10 + $i }}
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                            <a href="#" class="btn btn-sm btn-outline-secondary w-100">
                                                View details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Wishlist --}}
                    <div class="tab-pane fade" id="wishlist">
                        <div class="row g-3 g-md-4">
                            @for ($i = 1; $i <= 2; $i++)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="learning-card card h-100 border-0">
                                        <div class="learning-thumb ratio ratio-16x9">
                                            <div class="learning-thumb-inner">
                                                <span class="badge rounded-pill bg-light text-muted">
                                                    Wishlist
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title fw-semibold mb-1">
                                                Course Name {{ $i }}
                                            </h6>
                                            <p class="card-text text-muted small mb-2">
                                                You saved this for later.
                                            </p>
                                            <div class="xsmall text-muted">
                                                Est. 6h • Beginner friendly
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                                            <a href="#" class="btn btn-sm btn-primary w-100">
                                                Start this course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Edit Profile Modal（本人 or 管理者のみ） --}}
        @if ($canSeeAll)
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-3">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">
                                Edit profile
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <form action="{{ route('students.profile.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="edit-name" class="form-label">Name</label>
                                    <input type="text" id="edit-name" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" id="edit-email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                {{-- About --}}
                                <div class="mb-0">
                                    <label for="edit-about" class="form-label">About</label>
                                    <textarea id="edit-about" name="about" class="form-control" rows="4" placeholder="Tell us about yourself.">{{ old('about', $user->about) }}</textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary-solid">
                                    Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </section>
@endsection
