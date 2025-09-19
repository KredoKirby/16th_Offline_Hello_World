@extends('layouts.admin')

@section('title', 'Students')

@section('content')
    <div class="row g-0">

        {{-- サイドバー下の Logout --}}
        <div class="mt-auto w-100 px-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-link text-danger fw-bold p-0">Logout</button>
            </form>
        </div>
        </aside>

        {{-- ===== メインコンテンツ ===== --}}
        <main class="col bg-light p-4">
            <h2 class="mb-3">Students</h2>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>CREATED AT</th>
                                    <th>STATUS</th>
                                    <th class="text-end" style="width:64px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $row)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $row['avatar'] }}" alt="" class="rounded-circle me-3"
                                                    width="48" height="48">
                                                <span class="fw-semibold">{{ $row['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $row['email'] }}</td>
                                        <td>{{ $row['created_at'] }}</td>
                                        <td>
                                            @if ($row['active'])
                                                <span
                                                    class="badge rounded-pill bg-success-subtle border border-success text-success me-2">&nbsp;</span>
                                                Active
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-secondary-subtle border border-secondary text-secondary me-2">&nbsp;</span>
                                                Inactive
                                            @endif
                                        </td>
                                        <td class="text-end align-middle">
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0 m-0 text-dark fs-4 lh-1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">…</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <form method="POST"
                                                            action="{{ route('admin.students.toggle', $row['id']) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                {{ $row['active'] ? 'Inactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
