@extends('layouts.app')
@section('title', 'Students')

@section('content')
    <h2 class="mb-3">Students</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th style="background-color:#75E6DA;">NAME</th>
                            <th style="background-color:#75E6DA;">EMAIL</th>
                            <th style="background-color:#75E6DA;">COURSES</th>
                            <th style="background-color:#75E6DA;">ENROLLMENT</th>
                            <th class="text-end" style="width:64px; background-color:#75E6DA;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $row)
                            @php
                                $avatar = $row->avatar ? asset('storage/' . $row->avatar) : asset('images/avatar1.jpg');
                                $firstTwo = $row->courses->take(2);
                                $courseNames = $firstTwo->pluck('title')->join(', ');
                                $extra = max($row->courses_count - $firstTwo->count(), 0);
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $avatar }}" alt="avatar" class="rounded-circle me-3"
                                            width="48" height="48">
                                        <span class="fw-semibold">{{ $row->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $row->email }}</td>

                                {{-- COURSES --}}
                                <td>
                                    @if ($courseNames)
                                        {{ $courseNames }}
                                        @if ($extra > 0)
                                            <span class="text-muted">+{{ $extra }} more</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- ENROLLMENT --}}
                                <td><span class="fw-semibold">{{ $row->courses_count }}</span> courses</td>

                                <td class="text-end">…</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No students yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (method_exists($students, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
