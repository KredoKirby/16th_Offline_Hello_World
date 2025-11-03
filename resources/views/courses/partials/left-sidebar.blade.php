{{-- resources/views/courses/partials/left-sidebar.blade.php --}}

{{-- ===== PHP: クエリ整理 ===== --}}
@php
    $query = request()->query();
    $allQuery = $query;
    unset($allQuery['status']);
@endphp

{{-- ===== 検索フォーム ===== --}}
<form method="GET" action="{{ route('courses.index') }}" class="mb-3 d-flex align-items-center video-search-bar"
    style="width: 250px;">
    @foreach (request()->except('search') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <div class="input-group dashboard-search">
        <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
        <button type="submit" class="input-group-text bg-white" style="cursor:pointer;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</form>

{{-- ===== タブ ===== --}}
<ul class="nav custom-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request()->has('status') ? 'active' : '' }}"
            href="{{ route('courses.index', $allQuery) }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}"
            href="{{ route('courses.index', array_merge($query, ['status' => 'active'])) }}">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}"
            href="{{ route('courses.index', array_merge($query, ['status' => 'completed'])) }}">Completed</a>
    </li>
</ul>

{{-- ===== 言語フィルタ ===== --}}
<div class="mb-3">
    <a href="{{ route('courses.index', array_merge($query, ['lang' => 'english'])) }}"
        class="btn btn-outline-dark btn-sm me-1 {{ request('lang') == 'english' ? 'active' : '' }}">English</a>
    <a href="{{ route('courses.index', array_merge($query, ['lang' => 'it'])) }}"
        class="btn btn-outline-dark btn-sm {{ request('lang') == 'it' ? 'active' : '' }}">IT</a>
</div>

{{-- ===== コース一覧 ===== --}}
@foreach ($courses as $c)
    @php
        // 受講率（任意）
        $isEnrolled = in_array($c->id, $enrolledCourseIds ?? []);
        $rate = $isEnrolled && method_exists($c, 'completionRate') ? (int) $c->completionRate(auth()->id()) : 0;

        // タブによる絞り込み
        if (request('status') == 'active' && (!$isEnrolled || $rate == 100)) {
            continue;
        }
        if (request('status') == 'completed' && (!$isEnrolled || $rate < 100)) {
            continue;
        }

        // 言語フィルタ
        if (request('lang') && request('lang') != ($c->language ?? null)) {
            continue;
        }

        // 選択中ハイライト
        $isSelected = isset($selectedCourse)
            ? $selectedCourse->id === $c->id
            : (isset($course)
                ? $course->id === $c->id
                : false);

        // 画像（存在しない時のフォールバック）
        $img = $c->image ? asset('images/courses/' . $c->image) : asset('images/course-default.png');

        // Active/Inactive（bool想定: is_active）
        $active = (bool) ($c->is_active ?? true);
    @endphp

    <div class="list-group-item border-0 p-0 mb-2">

        {{-- ===== 1行目（ヘッダ） ===== --}}
        <div
            class="d-flex align-items-center p-2 rounded border
                {{ $isSelected ? 'bg-light border-primary shadow-sm' : 'shadow-sm' }}">

            {{-- 丸い写真 --}}
            {{-- <img src="{{ $img }}" alt="{{ $c->title ?? $c->name }}" class="rounded-circle me-3"
                width="48" height="48" style="object-fit:cover;"> --}}
            <img src="{{ $c->photo ? asset('storage/' . $c->photo) : asset('images/course-default.png') }}"
                alt="{{ $c->title ?? $c->name }}" class="rounded-circle me-3" width="48" height="48"
                style="object-fit:cover;">


            {{-- タイトル & 進捗/言語 --}}
            <div class="flex-grow-1">
                <a href="{{ route('courses.show', $c->id) }}" class="text-decoration-none text-dark">
                    <h6 class="mb-1 fw-bold">{{ $c->title ?? $c->name }}</h6>
                </a>

                @if ($isEnrolled)
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                    </div>
                    <small class="text-muted">{{ $rate }}% Finish</small>
                @else
                    <small class="badge bg-light text-dark border">
                        {{ $c->language ?? 'English' }}
                    </small>
                @endif
            </div>

            {{-- ステータス表示（緑の丸＋Active/Inactive） --}}
            <div class="me-2 d-flex align-items-center">
                <span class="status-dot {{ $active ? 'bg-success' : 'bg-secondary' }}"></span>
                <span class="ms-2">{{ $active ? 'Active' : 'Inactive' }}</span>
            </div>

            {{-- Activate / Inactivate ボタン（任意：ルートがある場合） --}}
            @if (Route::has('courses.toggle'))
                <form method="POST" action="{{ route('courses.toggle', $c) }}" class="me-2">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $active ? 'btn-secondary' : 'btn-success' }}">
                        {{ $active ? 'Inactivate' : 'Activate' }}
                    </button>
                </form>
            @endif

            {{-- 右端の折りたたみトグル（^） --}}
            <button class="btn btn-link text-decoration-none p-0" data-bs-toggle="collapse"
                data-bs-target="#topics-{{ $c->id }}" aria-expanded="false"
                aria-controls="topics-{{ $c->id }}">
                <span class="caret"></span>
            </button>
        </div>

        {{-- ===== 2行目（折りたたみ：Topics） ===== --}}
        <div id="topics-{{ $c->id }}" class="collapse">
            @if (view()->exists('courses.partials.topics'))
               @include('courses.partials.topics', ['course' => $c])
            @else
                <div class="bg-white border-top p-3 text-muted">
                    <em>Topics component not found. Create: resources/views/courses/partials/topics.blade.php</em>
                </div>
            @endif
        </div>


    </div>
@endforeach

{{-- ===== UI用ミニCSS（このファイル末尾にOK） ===== --}}
<style>
    .status-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .caret::after {
        content: '▾';
        display: inline-block;
        transition: transform .2s;
        font-size: 1rem;
    }

    [aria-expanded="true"] .caret::after {
        transform: rotate(180deg);
    }
</style>
