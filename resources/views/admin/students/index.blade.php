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
              <th style="background-color:#75E6DA;">CREATED AT</th>
              <th style="background-color:#75E6DA;">STATUS</th>
              <th class="text-end" style="width:64px; background-color:#75E6DA;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($students as $row)
            @php
              $avatar = $row->avatar ? asset('storage/'.$row->avatar) : asset('images/avatar1.jpg');
              $isActive = (bool)($row->active ?? $row->is_active ?? false);
            @endphp
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <img src="{{ $avatar }}" alt="avatar" class="rounded-circle me-3" width="48" height="48">
                  <span class="fw-semibold">{{ $row->name }}</span>
                </div>
              </td>
              <td>{{ $row->email }}</td>
              <td>{{ optional($row->created_at)->format('Y-m-d') }}</td>
              <td>
                @if ($isActive)
                  <span class="badge rounded-pill bg-success-subtle border border-success text-success me-2">&nbsp;</span>
                  Active
                @else
                  <span class="badge rounded-pill bg-secondary-subtle border border-secondary text-secondary me-2">&nbsp;</span>
                  Inactive
                @endif
              </td>
              <td class="text-end align-middle">
                <div class="dropdown">
                  <button class="btn p-0 m-0 text-dark fs-4 lh-1 d-flex justify-content-center align-items-center"
                          data-bs-toggle="dropdown" aria-expanded="false">…</button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <form method="POST" action="{{ route('admin.students.toggle', $row->id) }}">
                        @csrf
                        @method('PATCH') {{-- toggle は PATCH --}}
                        <button type="submit" class="dropdown-item">
                          {{ $isActive ? 'Inactivate' : 'Activate' }}
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No students yet</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- paginate() を使っている場合 --}}
  @if(method_exists($students, 'links'))
    <div class="mt-3">{{ $students->links() }}</div>
  @endif
@endsection
