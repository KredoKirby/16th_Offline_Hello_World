@php
    $roleId = Auth::user()->role_id ?? null;

    $isAdmin = (string) $roleId === '1';
    $isTeacher = (string) $roleId === '2';
    $isStudent = (string) $roleId === '3';
@endphp

@if ($isAdmin)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
         vh-100 position-sticky top-0 sidebar-shell"
        style="background-color:#9CDBE2;">

        {{-- Brand / Logo（装飾なし） --}}
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="brand-logo">
        </div>

        {{-- Main nav --}}
        <nav class="nav flex-column w-100 px-4 fw-semibold s-nav">
            <a class="nav-link s-link mb-1 {{ request()->routeIs('admin.index') ? 'active' : '' }}"
                href="{{ route('admin.index') }}">Home</a>
            <a class="nav-link s-link mb-1 {{ request()->routeIs('admin.students.index') ? 'active' : '' }}"
                href="{{ route('admin.students.index') }}">Students</a>
            <a class="nav-link s-link mb-1 {{ request()->routeIs('admin.teachers.index') ? 'active' : '' }}"
                href="{{ route('admin.teachers.index') }}">Teachers</a>
            <a class="nav-link s-link mb-1 {{ request()->routeIs('admin.courses.index') ? 'active' : '' }}"
                href="{{ route('admin.courses.index') }}">Courses</a>
            <a class="nav-link s-link mb-1" href="#">Self-learning</a>
            <a class="nav-link s-link" href="#">Forum</a>
        </nav>

        {{-- Footer / User --}}
        <div class="mt-auto w-100">
            <div class="nav flex-column w-100 px-4 fw-semibold">
                <div>
                    <a class="nav-link s-link d-flex align-items-center gap-2 p-0 bg-transparent text-start w-100"
                        href="{{ route('teachers.profile', ['user_id' => Auth::id()]) }}">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="text-truncate">{{ Auth::user()->name }}</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="nav-link s-link s-link-danger p-0 bg-transparent border-0 text-start w-100">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
@elseif($isTeacher)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
         vh-100 position-sticky top-0 sidebar-shell"
        style="background-color:#9CDBE2;">

        {{-- Brand / Logo（装飾なし） --}}
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="brand-logo">
        </div>

        {{-- Main nav --}}
        <nav class="nav flex-column w-100 px-4 fw-semibold s-nav">
            <a class="nav-link s-link mb-1 {{ request()->routeIs('teachers.index') ? 'active' : '' }}"
                href="{{ route('teachers.index') }}">Schedule</a>
            <a class="nav-link s-link mb-1 {{ request()->routeIs('courses.index') ? 'active' : '' }}"
                href="{{ route('courses.index') }}">Courses</a>
            <a class="nav-link s-link mb-1" href="#">Self-learning</a>
            <a class="nav-link s-link" href="#">Forum</a>
        </nav>

        {{-- Footer / User --}}
        <div class="mt-auto w-100">
            <div class="nav flex-column w-100 px-4 fw-semibold">
                <div>
                    <a class="nav-link s-link d-flex align-items-center gap-2 p-0 bg-transparent text-start w-100"
                        href="{{ route('teachers.profile', ['user_id' => Auth::id()]) }}">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="text-truncate">{{ Auth::user()->name }}</span>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="nav-link s-link s-link-danger p-0 bg-transparent border-0 text-start w-100">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
@elseif($isStudent)
    <aside
        class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center py-4
         vh-100 position-sticky top-0 sidebar-shell"
        style="background-color:#9CDBE2;">

        {{-- Brand / Logo（装飾なし） --}}
        <div class="mb-4">
            <img src="{{ asset('images/HELLO2.png') }}" alt="Hello World" class="brand-logo">
        </div>

        {{-- Main nav --}}
        <nav class="nav flex-column w-100 px-4 fw-semibold s-nav">
            <a class="nav-link s-link mb-1 {{ request()->routeIs('students.index') ? 'active' : '' }}"
                href="{{ route('students.index') }}">Home</a>
            <a class="nav-link s-link mb-1 {{ request()->routeIs('courses.index') ? 'active' : '' }}"
                href="{{ route('courses.index') }}">Courses</a>
            <a class="nav-link s-link mb-1" href="#">Self-learning</a>
            <a class="nav-link s-link" href="#">Forum</a>
        </nav>

        {{-- Footer / User --}}
        <div class="mt-auto w-100">
            <div class="nav flex-column w-100 px-4 fw-semibold">
                <div class="dropup">
                    <button
                        class="nav-link s-link d-flex align-items-center gap-2 dropdown-toggle p-0 bg-transparent border-0 text-start w-100 s-user-toggle"
                        id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle"></i>
                        <span class="text-truncate">{{ Auth::user()->name }}</span>
                    </button>

                    <ul class="dropdown-menu s-menu w-100 shadow border-0" aria-labelledby="userMenuButton">
                        <li>
                            <a class="dropdown-item s-menu-item {{ request()->routeIs('students.profile') ? 'active' : '' }}"
                                href="{{ route('students.profile', ['user_id' => Auth::user()]) }}">
                                <i class="fa-regular fa-user me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item s-menu-item {{ request()->routeIs('students.mylearning') ? 'active' : '' }}"
                                href="{{ route('students.mylearning') }}">
                                <i class="fa-solid fa-graduation-cap me-2"></i> My learning
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item s-menu-item {{ request()->routeIs('students.lessonhistory') ? 'active' : '' }}"
                                href="{{ route('students.lessonhistory') }}">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i> Lesson history
                            </a>
                        </li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="nav-link s-link s-link-danger p-0 bg-transparent border-0 text-start w-100">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
@else
    {{-- role不明時は何も表示しない（必要ならデフォルトを用意） --}}
@endif
