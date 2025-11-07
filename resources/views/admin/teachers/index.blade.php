@extends('layouts.app')
@section('title', 'Teachers')

@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold m-0">Teachers</h3>

    {{-- +Add（モーダル起動などに差し替え可） --}}
    <button type="button"
      class="btn rounded-3 fw-semibold px-3 text-white"
      style="background-color:#05445E; border-color:#05445E;"
      data-bs-toggle="modal" data-bs-target="#addTeacherModal">
      + Add
    </button>
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
            <th style="background-color:#7EEAFF;">Courses</th>      {{-- ← Studentsと同じ表現 --}}
            <th style="background-color:#7EEAFF;">Enrollment</th>   {{-- ← 件数表示 --}}
            <th class="text-end" style="background-color:#7EEAFF;"></th>
          </tr>
        </thead>

        <tbody>
        @forelse ($teachers as $t)
          @php
            $avatar = $t->avatar ? asset('storage/'.$t->avatar) : asset('images/avatar1.jpg');

            // 先頭2件の担当コース名（title）
            $firstTwo   = $t->coursesTaught?->take(2) ?? collect();
            $coursesTxt = $firstTwo->pluck('title')->filter()->join(', ');
            $more       = max(($t->courses_taught_count ?? 0) - $firstTwo->count(), 0);
          @endphp

          <tr>
            {{-- NAME --}}
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="{{ $avatar }}" class="rounded-circle" width="40" height="40" alt="avatar">
                <span class="fw-medium">{{ $t->name }}</span>
              </div>
            </td>

            {{-- EMAIL --}}
            <td class="text-muted">{{ $t->email }}</td>

            {{-- COURSES --}}
            <td>
              @if ($coursesTxt !== '')
                {{ $coursesTxt }}
                @if ($more > 0)
                  <span class="text-muted">+{{ $more }} more</span>
                @endif
              @else
                -
              @endif
            </td>

            {{-- ENROLLMENT（担当コース数） --}}
            <td><span class="fw-semibold">{{ $t->courses_taught_count ?? 0 }}</span> courses</td>

            {{-- ACTIONS（必要に応じて編集/トグル等） --}}
            <td class="text-end">
              <div class="dropdown">
                <button class="btn p-0 m-0 text-dark fs-4 lh-1" data-bs-toggle="dropdown" aria-expanded="false">…</button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                  <li><a class="dropdown-item" href="{{ route('admin.teachers.edit', $t->id) }}">Edit</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form method="POST" action="{{ route('admin.teachers.toggle', $t->id) }}">
                      @csrf
                      @method('PATCH')
                      <button class="dropdown-item" type="submit">Toggle</button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">No teachers yet</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ページネーション --}}
  @if(method_exists($teachers, 'links'))
    <div class="mt-3">{{ $teachers->links() }}</div>
  @endif
</div>

{{-- 追加モーダル（必要なら） --}}
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Add a teacher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('admin.teachers.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" type="text" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">+ Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
