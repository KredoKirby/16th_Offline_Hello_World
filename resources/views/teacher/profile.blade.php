@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="container w-75 py-4">
        @php
            $viewer = auth()->user();
            $viewerRoleId = (int) ($viewer->role_id ?? 0);
            // 便利フラグ
            $viewerIsAdmin = $viewerRoleId === 1 || ($viewer->role ?? null) === 'admin'; // 既存の 'admin' 文字列にも対応
            $viewerIsTeacher = $viewerRoleId === 2 || ($viewer->role ?? null) === 'teacher';

            // ボタンの可視判定（= 管理者は常にOK／先生は“自分のプロフィール”のみOK）
            $isOwner = optional($viewer)->id === optional($user)->id;

            // 「学生/一般」に見せない項目の可視判定（= 管理者 or 先生は“自分のプロフィール”のみOK）
            $canSeePrivate = $viewerIsAdmin || ($viewerIsTeacher && $isOwner);

            
            $canEdit = $viewerIsAdmin || ($viewerIsTeacher && $isOwner);
        @endphp

        {{-- ===== Profile Card ===== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-md-4 text-center">
                        <img src="{{ $user->avatar_url }}" class="rounded-3 object-fit-cover profile-photo mb-3"
                            alt="">
                    </div>

                    <div class="col-12 col-md-8">
                        <h2 class="h3 mb-4 mt-2">{{ $user->name }}</h2>
                        <dl class="row mb-0">
                            {{-- Email（admin/teacher のみ表示） --}}
                            @if ($canSeePrivate)
                                <dt class="col-4 col-sm-3 mb-2">Email:</dt>
                                <dd class="col-8 col-sm-9 mb-2">{{ $user->email }}</dd>
                            @endif

                            <dt class="col-4 col-sm-3 mb-2">About:</dt>
                            <dd class="col-8 col-sm-9 mb-2">{{ $user->about }}</dd>

                            {{-- Meeting URL（admin/teacher のみ表示） --}}
                            @if ($canSeePrivate)
                                <dt class="col-4 col-sm-3">Meeting URL:</dt>
                                <dd class="col-8 col-sm-9">
                                    @if ($user->meeting_url)
                                        <a href="{{ $user->meeting_url }}" target="_blank"
                                            rel="noopener">{{ $user->meeting_url }}</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </dd>
                            @endif
                        </dl>
                    </div>

                    {{-- ボタン行（admin は常にOK、teacher は“自分のプロフィール”だけOK、student/basic は非表示） --}}
                    @if ($canEdit)
                        <div class="row g-3 align-items-center mt-2">
                            <div class="col-12 col-md-4 text-center">
                                <form method="POST" action="#" enctype="multipart/form-data" class="mb-0">
                                    @csrf
                                    <label class="btn btn-outline-secondary mb-0">
                                        <input type="file" name="photo" accept="image/*" class="d-none"
                                            onchange="this.form.submit()">
                                        Upload Photo
                                    </label>
                                </form>
                            </div>
                            <div class="col-12 col-md-8 d-flex justify-content-md-start justify-content-center">
                                <a class="btn btn-teal fw-semibold px-4" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        {{-- ===== Modal ===== --}}
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('teachers.profile.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input name="name" type="text" class="form-control"
                                    value="{{ old('name', $user->name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input name="email" type="email" class="form-control"
                                    value="{{ old('email', $user->email ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">About</label>
                                <textarea name="about" rows="4" class="form-control" placeholder="Tell us about yourself">{{ old('about', $user->about ?? '') }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold">Meeting URL</label>
                                <input name="meeting_url" type="url" class="form-control"
                                    value="{{ old('meeting_url', $user->meeting_url ?? '') }}"
                                    placeholder="https://example.com/meet/your-room">
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== Courses ===== --}}
        <div class="row mt-5">
            <div class="col-4">
                <h3 class="h5 mb-3">Courses</h3>
            </div>

            <div class="col-8">
                @if (auth()->user()?->role === 'admin')
                    <form method="POST" action="{{ route('teachers.courses.attach', $user->id) }}"
                        class="row g-2 align-items-center mb-3">
                        @csrf
                        <div class="col-12 col-sm-6 col-md-6 ms-auto">
                            <select name="course_id" class="form-select" required>
                                <option value="" disabled selected>Add a course…</option>
                                @foreach ($allCourses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <button type="submit" class="btn btn-primary px-4">Add</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course Name</th>
                            @if (auth()->user()?->role === 'admin')
                                <th class="text-end">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                @if (auth()->user()?->role === 'admin')
                                    <td class="text-end">
                                        <form method="POST"
                                            action="{{ route('teachers.courses.detach', [$user->id, $course->id]) }}"
                                            onsubmit="return confirm('Delete this course from the teacher?');"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->role === 'admin' ? 2 : 1 }}" class="text-muted">
                                    No courses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

    <style>
        .profile-photo {
            width: 220px;
            height: 220px;
        }

        .btn-teal {
            background: #45dacd;
            border-color: #45dacd;
            color: #0b2a2e;
        }

        .btn-teal:hover {
            filter: brightness(.95);
        }
    </style>
@endsection
