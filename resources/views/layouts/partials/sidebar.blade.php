@php
    $roleId = Auth::user()->role_id ?? null;

    $isAdmin = (string) $roleId === '1';
    $isTeacher = (string) $roleId === '2';
    $isStudent = (string) $roleId === '3';
@endphp

@if ($isAdmin)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
                vh-100 position-sticky top-0"
        style="background-color:#9CDBE2;">
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="img-fluid rounded-circle"
                style="max-width:150px;">
        </div>

        <nav class="nav flex-column w-100 px-4 fw-bold">
            {{-- Home --}}
            <a class="nav-link text-dark" href="{{ route('admin.index') }}">Home</a>

            {{-- Students --}}
            <a class="nav-link text-dark" href="{{ route('admin.students.index') }}">Students</a>

            {{-- Teachers --}}
            <a class="nav-link text-dark" href="{{ route('admin.teachers.index') }}">Teachers</a>

            {{-- Courses --}}
            <a class="nav-link text-dark" href="{{ route('admin.courses.index') }}">Courses</a>

            {{-- Self-learning（未実装なら # のままでも可） --}}
            <a class="nav-link text-dark" href="#">Self-learning</a>

            {{-- Forums --}}
            <a class="nav-link text-dark" href="">Forum</a>
        </nav>

        <div class="mt-auto text-left w-100 px-5">
            <div class="small fw-bold">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-link text-danger fw-bold p-0">Logout</button>
            </form>
        </div>
    </aside>
@elseif($isTeacher)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
                vh-100 position-sticky top-0"
        style="background-color:#9CDBE2;">
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="img-fluid rounded-circle"
                style="max-width:150px;">
        </div>

        <nav class="nav flex-column w-100 px-4 fw-bold">
            {{-- Home --}}
            <a class="nav-link text-dark" href="{{ route('admin.index') }}">Home</a>

            {{-- Students --}}
            <a class="nav-link text-dark" href="{{ route('admin.students.index') }}">Students</a>

            {{-- Teachers --}}
            <a class="nav-link text-dark" href="{{ route('admin.teachers.index') }}">Teachers</a>

            {{-- Courses --}}
            <a class="nav-link text-dark" href="{{ route('admin.courses.index') }}">Courses</a>

            {{-- Self-learning（未実装なら # のままでも可） --}}
            <a class="nav-link text-dark" href="#">Self-learning</a>

            {{-- Forums --}}
            <a class="nav-link text-dark" href="">Forum</a>
        </nav>

        <div class="mt-auto text-left w-100 px-5">
            <div class="small fw-bold">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-link text-danger fw-bold p-0">Logout</button>
            </form>
        </div>
    </aside>
@elseif($isStudent)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
                vh-100 position-sticky top-0 sidebar"
        style="background-color:#9CDBE2;">
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="img-fluid rounded-circle"
                style="max-width:150px;">
        </div>

        <nav class="nav flex-column w-100 px-4 fw-bold">
            {{-- Home --}}
            <a class="nav-link mb-2 {{ request()->routeIs('students.index') ? 'active' : '' }}"
                href="{{ route('students.index') }}">Home</a>

            {{-- Courses --}}
            <a class="nav-link mb-2 {{ request()->routeIs('courses.index') ? 'active' : '' }}"
                href="{{ route('courses.index') }}">Courses</a>

            {{-- Self-learning（未実装なら # のままでも可） --}}
            <a class="nav-link mb-2" href="#">Self-learning</a>

            {{-- Forums --}}
            <a class="nav-link" href="">Forum</a>
        </nav>
        <div class="mt-auto w-100">
            <div class="nav flex-column w-100 px-4 fw-bold">
                <div class="dropup">
                    <button
                        class="nav-link d-flex align-items-center gap-2 dropdown-toggle p-0 bg-transparent border-0 text-start w-100"
                        id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="color: inherit;">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="text-truncate">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu w-100 shadow border-0" aria-labelledby="userMenuButton">
                        <li>
                            <a class="dropdown-item fw-semibold {{ request()->routeIs('students.profile') ? 'active' : '' }}"
                                href="{{ route('students.profile', ['user_id' => Auth::user()]) }}">
                                <i class="fa-regular fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-semibold {{ request()->routeIs('students.mylearning') ? 'active' : '' }}"
                                href="{{ route('students.mylearning') }}">
                                <i class="fa-solid fa-graduation-cap me-2"></i> My learning
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-semibold {{ request()->routeIs('students.lessonshistory') ? 'active' : '' }}"
                                href="{{ route('students.lessonhistory') }}">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i> Lesson history
                            </a>
                        </li>
                    </ul>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="nav-link p-0 bg-transparent border-0 text-start w-100 fw-bold"
                        style="color:#DB1F48;">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>

            </div>
        </div>
    </aside>
@else
    {{-- role不明時は何も表示しない（必要ならデフォルトを用意） --}}
@endif
