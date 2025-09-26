@extends('layouts.admin')
@section('title', 'Teachers')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold m-0">Teachers</h3>
            <a href="{{ route('admin.teachers.create') }}" class="btn rounded-3 fw-semibold px-3 text-white"
                style="background-color:#05445E; border-color:#05445E;">
                + Add
            </a>

        </div>

        @if (session('status'))
            <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif

        <div class="card shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">

                    <thead class="fw-bold text-uppercase" style="background-color:#7EEAFF;">
                        <tr>
                            <th style="background-color:#7EEAFF;">Name</th>
                            <th style="background-color:#7EEAFF;">Email</th>
                            <th style="background-color:#7EEAFF;">Created At</th>
                            <th style="background-color:#7EEAFF;">Status</th>
                            <th class="text-end" style="background-color:#7EEAFF;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items ?? [] as $t)
                            @php $isActive = !empty($t['active']); @endphp
                            <tr>
                                {{-- NAME + AVATAR --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $t['avatar'] ?? asset('images/avatar1.jpg') }}" class="rounded-circle"
                                            width="40" height="40" alt="avatar">
                                        <span class="fw-medium">{{ $t['name'] }}</span>
                                    </div>
                                </td>

                                {{-- EMAIL --}}
                                <td class="text-muted">{{ $t['email'] }}</td>

                                {{-- CREATED --}}
                                <td class="text-muted">{{ $t['created_at'] ?? '-' }}</td>

                                {{-- STATUS --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="rounded-circle d-inline-block {{ $isActive ? 'bg-success' : 'bg-secondary' }}"
                                            style="width:12px; height:12px;"></span>
                                        <span class="{{ $isActive ? '' : 'text-muted' }}">
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- ACTION --}}
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button
                                            class="btn p-0 m-0 text-dark fs-4 lh-1 d-flex justify-content-center align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">…</button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.teachers.edit', $t['id']) }}">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form method="POST"
                                                    action="{{ route('admin.teachers.toggle', $t['id']) }}">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">
                                                        {{ $isActive ? 'Inactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No teachers yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
