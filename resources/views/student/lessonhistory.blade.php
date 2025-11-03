@extends('layouts.app')
@section('title', 'Lesson history')

@section('content')
    <section class="container py-4">
        <h1 class="h4 mb-3">Lesson history</h1>

        <div class="vstack gap-3">
            @forelse ($bookings as $b)
                @php
                    $dt = \Carbon\Carbon::parse($b->date . ' ' . $b->time)->timezone(config('app.timezone'));
                    $duration = $b->duration_minutes ?? 50;
                    $end = (clone $dt)->addMinutes($duration);

                    $course = $b->course->title ?? 'Course';
                    $topic = $b->topic->name ?? 'Topic';
                    $teacher = $b->teacher->name ?? 'Teacher';
                    $iconUrl = $b->course->icon_url ?? asset('images/placeholder-course.png');
                    $whenStr = $dt->format('D, M j H:i') . '–' . $end->format('H:i');

                    // report (optional)
                    $status = $b->report->status ?? null;
                    $nextTop = $b->report->next_topic ?? '—';
                    $statusClass = match (strtolower((string) $status)) {
                        'done', 'completed' => 'text-bg-success',
                        'pending', 'todo' => 'text-bg-warning',
                        'missed', 'absent' => 'text-bg-danger',
                        default => 'text-bg-secondary',
                    };
                @endphp

                <div class="card shadow">
                    <div class="card-body py-3 px-3">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <img src="{{ $iconUrl }}" alt="Course icon" class="rounded-3 border flex-shrink-0"
                                style="width:48px;height:48px;object-fit:cover;">

                            <div class="min-w-0 flex-grow-1">
                                <div class="fw-semibold fs-5 text-truncate">
                                    {{ $course }} <span class="text-body-secondary">·</span> {{ $topic }}
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2 mt-1 text-secondary small">
                                    <span class="d-inline-flex align-items-center">
                                        <i class="fa-regular fa-calendar me-1"></i>{{ $whenStr }}
                                    </span>
                                    <span>•</span>
                                    <span>with <span class="text-body">{{ $teacher }}</span></span>
                                </div>
                            </div>

                            {{-- Details opens modal --}}
                            <div class="d-flex gap-2 ms-auto">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-toggle="modal"
                                    data-bs-target="#bookingDetails-{{ $b->id }}">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal (one per booking) --}}
                <div class="modal fade" id="bookingDetails-{{ $b->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Booking details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Booking block --}}
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-regular fa-calendar-check text-primary"></i>
                                        <span class="text-uppercase text-muted small fw-semibold">Booking</span>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-clone"></i><span>Course</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $course }}">{{ $course }}</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-bookmark"></i><span>Topic</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $topic }}">{{ $topic }}</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-user"></i><span>Teacher</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $teacher }}">{{ $teacher }}</div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-clock"></i><span>Date & time</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $whenStr }}">{{ $whenStr }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                {{-- Report block --}}
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-regular fa-clipboard text-primary"></i>
                                        <span class="text-uppercase text-muted small fw-semibold">Report</span>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-flag"></i><span>Status</span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="badge {{ $statusClass }}">{{ $status ?? '—' }}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-lightbulb"></i><span>Next topic</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $nextTop }}">{{ $nextTop }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border d-flex align-items-center gap-2 mb-0">
                    <i class="fa-regular fa-circle-info text-secondary"></i>
                    <span class="small">No history yet.</span>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </section>
@endsection
