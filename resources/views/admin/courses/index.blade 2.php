@extends('layouts.app')
@section('title', 'Courses')

@section('content')
<div class="container-fluid py-2">

  {{-- ヘッダー --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="m-0 fw-bold text-dark">Courses</h2>
    <a href="{{ route('admin.courses.create') }}" 
       class="btn fw-bold rounded-pill px-3"
       style="background-color:#05445E; color:white;">
      ＋ Add a course
    </a>
  </div>

  {{-- 見出し行 --}}
  <div class="d-flex px-3 py-2 mb-2 fw-bold text-uppercase small rounded"
       style="background-color:#ECFF7E; color:#05445E;">
    <div class="me-3" style="width:90px;">Photo</div>
    <div class="flex-grow-1">Name</div>
  </div>

  {{-- 本体 --}}
  <div class="accordion" id="coursesAccordion">
    @forelse ($courses as $course)
      @php $isOpen = $loop->first; @endphp

      <div class="accordion-item border rounded-3 shadow-sm mb-3">
        <h2 class="accordion-header" id="heading-{{ $course->id }}">
          <button class="accordion-button {{ $isOpen ? '' : 'collapsed' }}" type="button"
                  data-bs-toggle="collapse" data-bs-target="#collapse-{{ $course->id }}"
                  aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="collapse-{{ $course->id }}"
                  style="background-color:white;">
            <div class="d-flex align-items-center w-100 gap-3">
              @php
                $src = $course->image
                      ? asset('storage/' . $course->image)
                      : asset('images/default-course.png');
              @endphp
              <img src="{{ $src }}" alt="{{ $course->name }}"
                   class="rounded-circle border shadow-sm" width="48" height="48">
              <span class="fw-semibold text-dark">{{ $course->name }}</span>
            </div>
          </button>
        </h2>

        <div id="collapse-{{ $course->id }}" 
             class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
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
                  @foreach (['Topic A', 'Topic B', 'Topic C'] as $i => $topic)
                    @php $isActive = $i % 2 === 0; @endphp
                    <tr>
                      <td class="ps-4 fw-semibold">{{ $topic }}</td>
                      <td>
                        @if ($isActive)
                          <span class="text-success fw-semibold">● Active</span>
                        @else
                          <span class="text-secondary fw-semibold">● Inactive</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            {{-- Add a topic --}}
            <div class="text-center p-3">
              <button class="btn fw-bold btn-sm rounded-pill px-4 shadow-sm"
                      style="background-color:#05445E; color:white;">
                Add a topic
              </button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="alert alert-light border text-center py-4">
        No courses yet.
      </div>
    @endforelse
  </div>
</div>
@endsection
