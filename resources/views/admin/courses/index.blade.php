@extends('layouts.app')
@section('title', 'Courses')

@section('content')
    <div class="container-fluid py-2">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="m-0 fw-bold text-dark">Courses</h2>
            <a href="{{ route('admin.courses.create') }}" class="btn fw-bold rounded-pill px-3 btn-dark">
                ＋ Add a course
            </a>
        </div>

        {{-- List head --}}
        <div class="row px-3 py-2 mb-2 fw-bold text-uppercase small rounded bg-warning-subtle text-dark">
            <div class="col-auto">Photo</div>
            <div class="col">Name</div>
            <div class="col-auto text-end">Status</div>
        </div>

        {{-- Body --}}
        <div class="accordion" id="coursesAccordion">
            @forelse ($courses as $course)
                @php $isOpen = request('open') == $course->id; @endphp

                <div class="accordion-item border rounded-3 shadow-sm mb-3">
                    <h2 class="accordion-header" id="heading-{{ $course->id }}">
                        @php
                            $src = $course->image
                                ? asset('storage/' . $course->image)
                                : asset('images/default-course.png');

                            $isCourseActive = data_get($course, 'is_active');
                            if (is_null($isCourseActive)) {
                                $isCourseActive = strtolower((string) data_get($course, 'status')) === 'active';
                            }
                        @endphp

                        <div class="d-flex align-items-center w-100 px-3 gap-3 flex-nowrap">
                            <div class="accordion-button {{ $isOpen ? '' : 'collapsed' }} py-2 bg-white flex-grow-1"
                                role="button" tabindex="0" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $course->id }}"
                                aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $course->id }}">

                                <div class="d-flex align-items-center w-100 gap-3 flex-nowrap">

                                    <img src="{{ $src }}" alt="{{ $course->title }}"
                                        class="rounded-circle border shadow-sm" width="56" height="56">

                                    {{-- Course title --}}
                                    <a href="{{ route('admin.courses.show', $course->id) }}"
                                        class="fw-semibold text-dark text-truncate flex-grow-1 text-decoration-none"
                                        style="display:block;" onclick="event.stopPropagation();">
                                        {{ $course->title }}
                                    </a>

                                    {{--  Toggle --}}
                                    <form method="POST"
                                          action="{{ route('admin.courses.toggle', $course) }}?open={{ $course->id }}"
                                          class="ms-2 flex-shrink-0"
                                          onclick="event.stopPropagation();">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="to" value="{{ $isCourseActive ? 'deactive' : 'active' }}">
                                        <button type="submit"
                                            class="btn btn-sm rounded-pill px-3 {{ $isCourseActive ? 'btn-secondary' : 'btn-success' }}">
                                            {{ $isCourseActive ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    {{-- Status label --}}
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        @if ($isCourseActive)
                                            <span class="text-success">●</span>
                                            <span class="fw-semibold text-success">Active</span>
                                        @else
                                            <span class="text-secondary">●</span>
                                            <span class="fw-semibold text-secondary">Deactivated</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </h2>

                    {{-- Topics --}}
                    <div id="collapse-{{ $course->id }}" class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
                        aria-labelledby="heading-{{ $course->id }}" data-bs-parent="#coursesAccordion">
                        <div class="accordion-body bg-white border-top">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Topic</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($course->topics as $topic)
                                            @php $isTopicActive = (int) $topic->status === 1; @endphp

                                            <tr>
                                                <td class="ps-4 fw-semibold w-50">
                                                    {{ $topic->name ?? ($topic->title ?? 'Topic #' . $topic->id) }}
                                                </td>
                                                <td class="w-25">
                                                    @if ($isTopicActive)
                                                        <span class="text-success fw-semibold">● Active</span>
                                                    @else
                                                        <span class="text-secondary fw-semibold">● Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    {{-- Topic Toggle --}}
                                                    <form method="POST"
                                                          action="{{ route('admin.topics.toggle', $topic) }}?open={{ $course->id }}"
                                                          class="d-inline" onclick="event.stopPropagation();">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="to" value="{{ $isTopicActive ? 'deactive' : 'active' }}">
                                                        <button type="submit"
                                                            class="btn btn-sm rounded-pill px-3 {{ $isTopicActive ? 'btn-secondary' : 'btn-success' }}">
                                                            {{ $isTopicActive ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">No topics yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="alert alert-light border text-center py-4">No courses yet.</div>
            @endforelse
        </div>
    </div>
@endsection
