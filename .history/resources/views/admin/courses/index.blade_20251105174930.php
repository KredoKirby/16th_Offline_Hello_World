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

  {{-- List head (Photo / Name / Status) --}}
  <div class="row px-3 py-2 mb-2 fw-bold text-uppercase small rounded bg-warning-subtle text-dark">
    <div class="col-auto">Photo</div>
    <div class="col">Name</div>
    <div class="col-auto text-end">Status</div>
  </div>

  {{-- Body --}}
  <div class="accordion" id="coursesAccordion">
    @forelse ($courses as $course)
      @php $isOpen = $loop->first; @endphp

      <div class="accordion-item border rounded-3 shadow-sm mb-3">
        <h2 class="accordion-header" id="heading-{{ $course->id }}">
          @php
            $src = $course->image ? asset('storage/' . $course->image) : asset('images/default-course.png');
            $isCourseActive = data_get($course, 'is_active');
            if (is_null($isCourseActive)) {
              $isCourseActive = strtolower((string) data_get($course, 'status')) === 'active';
            }
          @endphp

          {{-- Row header --}}
          <div class="d-flex align-items-center w-100 px-3 gap-3 flex-nowrap">
            <div class="accordion-button {{ $isOpen ? '' : 'collapsed' }} py-2 bg-white flex-grow-1"
                 role="button" tabindex="0" data-bs-toggle="collapse"
                 data-bs-target="#collapse-{{ $course->id }}"
                 aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                 aria-controls="collapse-{{ $course->id }}">
              <div class="d-flex align-items-center w-100 gap-3 flex-nowrap">

                <img src="{{ $src }}" alt="{{ $course->title }}"
                     class="rounded-circle border shadow-sm" width="56" height="56">

                {{-- Course title (link to show) --}}
                <a href="{{ route('admin.courses.show', $course->id) }}"
                   class="fw-semibold text-dark text-truncate flex-grow-1 text-decoration-none"
                   style="display:block;" onclick="event.stopPropagation();">
                  {{ $course->title }}
                </a>

                {{-- Right Action: toggle --}}
                <form method="POST" action="{{ route('admin.courses.toggle', $course) }}"
                      class="ms-2 flex-shrink-0" onclick="event.stopPropagation();">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="to" value="{{ $isCourseActive ? 'inactive' : 'active' }}">
                  <button type="submit"
                          class="btn btn-sm rounded-pill px-3 {{ $isCourseActive ? 'btn-secondary' : 'btn-success' }}">
                    {{ $isCourseActive ? 'Deactivate' : 'Activate' }}
                  </button>
                </form>

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

        {{-- Collapse (lessons table) --}}
        <div id="collapse-{{ $course->id }}"
             class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
             aria-labelledby="heading-{{ $course->id }}" data-bs-parent="#coursesAccordion">

          <div class="accordion-body bg-white border-top">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th class="ps-4">Lesson</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($course->lessons as $lesson)
                    <tr>
                      <td class="ps-4 fw-semibold w-50">{{ $lesson->title }}</td>
                      <td class="w-25">
                        {{-- lessons に状態カラムが無い想定：一旦 Active 固定表示 --}}
                        <span class="text-success fw-semibold">● Active</span>
                      </td>
                      <td class="text-end">
                        <button class="btn btn-sm rounded-pill px-3 btn-secondary" disabled>
                          Deactivate
                        </button>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-muted">No lessons yet</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            {{-- Add a topic --}}
            {{-- <div class="text-center p-3">
              <button class="btn fw-bold btn-sm rounded-pill px-4 shadow-sm"
                      style="background-color:#05445E; color:white;">
                Add a topic
              </button>
            </div> --}}
            
            <div class="text-center p-3">
              <a href="{{ route('admin.courses.show', $course->id) }}"
                 class="btn fw-bold btn-sm rounded-pill px-4 btn-dark"
                 onclick="event.stopPropagation();">
                Add a lesson
              </a>
            </div>
>>>>>>> 8fb9f542d53e17facde9a4a71c71e1c414fa59ad
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-light border text-center py-4">No courses yet.</div>
    @endforelse
  </div>
</div>
@endsection
