@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @php
        // ===== Demo data (fallback) =====
        $user ??= (object) [
            'name' => 'Shinya Nakaguchi',
            'email' => 'test@example.com',
            'about' =>
                'ttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt',
            'zoom_url' => 'https://zoom.us/j/1234567890',
            'avatar_url' => 'https://picsum.photos/400?grayscale',
        ];

        $skills ??= collect([(object) ['id' => 1, 'name' => 'PHP'], (object) ['id' => 2, 'name' => 'Laravel']]);
    @endphp

    <section class="container w-75 py-4">

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
                            <dt class="col-4 col-sm-3 mb-2">Email:</dt>
                            <dd class="col-8 col-sm-9 mb-2">{{ $user->email }}</dd>
                            <dt class="col-4 col-sm-3 mb-2">About:</dt>
                            <dd class="col-8 col-sm-9 mb-2">{{ $user->about }}</dd>
                            <dt class="col-4 col-sm-3">Zoom URL:</dt>
                            <dd class="col-8 col-sm-9"><a href="{{ $user->zoom_url }}" target="_blank"
                                    rel="noopener">{{ $user->zoom_url }}</a></dd>
                        </dl>
                    </div>

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

                </div>
            </div>
        </div>
        {{-- ===== Modal ===== --}}
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="#">
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
                                <label class="form-label fw-bold">Zoom URL</label>
                                <input name="zoom_url" type="url" class="form-control"
                                    value="{{ old('zoom_url', $user->zoom_url ?? '') }}">
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

        {{-- ===== Skills ===== --}}
        <div class="row mt-5">
            <div class="col-4">
                <h3 class="h5 mb-3">My skill</h3>
            </div>
            <div class="col-8">
                <form method="POST" action="#" class="row g-2 align-items-center mb-3">
                    @csrf
                    <div class="col-12 col-sm-6 col-md-4 ms-auto">
                        <input type="text" name="name" class="form-control" placeholder="Add a skill…" required>
                    </div>
                    <div class="col-12 col-sm-auto">
                        <button type="submit" class="btn btn-primary px-4">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                            <tr>
                                <td>{{ $skill->name }}</td>
                                <td class="text-end">
                                    <form method="POST" action="#" onsubmit="return confirm('Delete this skill?');"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
