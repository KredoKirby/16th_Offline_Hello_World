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
    @foreach ($items as $row)
      @php $isOpen = $loop->first; @endphp

      <div class="accordion-item border rounded-3 shadow-sm mb-3">
        <h2 class="accordion-header" id="heading-{{ $row['id'] }}">
          <button class="accordion-button {{ $isOpen ? '' : 'collapsed' }}" type="button"
                  data-bs-toggle="collapse" data-bs-target="#collapse-{{ $row['id'] }}"
                  aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="collapse-{{ $row['id'] }}"
                  style="background-color:white;">
            <div class="d-flex align-items-center w-100 gap-3">
              @php
                $src = !empty($row['image']) ? asset('storage/' . $row['image']) : asset('images/default-course.png');
              @endphp
              <img src="{{ $src }}" alt="{{ $row['name'] }}"
                   class="rounded-circle border shadow-sm" width="48" height="48">
              <span class="fw-semibold text-dark">{{ $row['name'] }}</span>
            </div>
          </button>
        </h2>

        <div id="collapse-{{ $row['id'] }}" 
             class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
             aria-labelledby="heading-{{ $row['id'] }}" data-bs-parent="#coursesAccordion">

          <div class="accordion-body bg-white border-top">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <tbody>
                  @foreach (['Topic Name','Topic Name','Topic Name','Topic Name'] as $i => $topic)
                    @php $topicActive = $i % 2 === 0; @endphp
                    <tr>
                      <td class="ps-4 fw-semibold">{{ $topic }}</td>
                      <td>
                        <div class="d-inline-flex align-items-center gap-2">
                          @if ($topicActive)
                            <span class="text-success">●</span><span>Active</span>
                          @else
                            <span class="text-secondary">●</span><span>Inactive</span>
                          @endif
                        </div>
                      </td>
                      <td class="text-end text-nowrap">
                        
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
    @endforeach
  </div>
</div>
@endsection
