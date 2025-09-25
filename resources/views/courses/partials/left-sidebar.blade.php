{{-- 左サイド共通パーツ --}}
@php
$query = request()->query();
$allQuery = $query;
unset($allQuery['status']); // Allタブでは status を削除
@endphp

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('courses.index') }}" class="d-flex align-items-center mb-3" style="width: 60%;">
    @foreach(request()->except('search') as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <input type="text" name="search" class="form-control form-control-sm me-1 rounded-pill" placeholder="Search" value="{{ request('search') }}">
    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="submit">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
</form>

{{-- タブ --}}
<ul class="nav custom-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request()->has('status') ? 'active' : '' }}" 
           href="{{ route('courses.index', $allQuery) }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status')=='active' ? 'active' : '' }}" 
           href="{{ route('courses.index', array_merge($query, ['status'=>'active'])) }}">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status')=='completed' ? 'active' : '' }}" 
           href="{{ route('courses.index', array_merge($query, ['status'=>'completed'])) }}">Completed</a>
    </li>
</ul>

{{-- 言語フィルタ --}}
<div class="mb-3">
    <a href="{{ route('courses.index', array_merge($query, ['lang'=>'english'])) }}" 
       class="btn btn-outline-dark btn-sm me-1 {{ request('lang')=='english'?'active':'' }}">English</a>
    <a href="{{ route('courses.index', array_merge($query, ['lang'=>'it'])) }}" 
       class="btn btn-outline-dark btn-sm {{ request('lang')=='it'?'active':'' }}">IT</a>
</div>

{{-- コース一覧 --}}
@foreach($courses as $c)
    @php
        $isEnrolled = in_array($c->id, $enrolledCourseIds ?? []);
        $rate = $isEnrolled ? $c->completionRate(auth()->id()) : 0;

        // タブによる絞り込み
        if(request('status')=='active' && (!$isEnrolled || $rate==100)) continue;
        if(request('status')=='completed' && (!$isEnrolled || $rate<100)) continue;

        // 言語フィルタ
        if(request('lang') && request('lang') != $c->language) continue;

        // 選択中のコース判定
        $isSelected = isset($selectedCourse) ? $selectedCourse->id === $c->id : (isset($course) ? $course->id === $c->id : false);
    @endphp

    <a href="{{ route('courses.show', $c->id) }}" class="text-decoration-none text-dark">
        <div class="d-flex align-items-center mb-3 p-2 rounded border 
                    {{ $isSelected ? 'bg-light border-primary shadow-sm' : 'shadow-sm' }}">
            <img src="{{ asset('images/courses/' . $c->image) }}" 
                 alt="{{ $c->title }}"
                 class="rounded me-2" style="width:60px;height:60px;object-fit:cover;">
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-bold">{{ $c->title }}</h6>
                @if($isEnrolled)
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar bg-info" style="width: {{ $rate }}%;"></div>
                    </div>
                    <small class="text-muted">{{ $rate }}% Finish</small>
                @else
                    <small class="badge bg-light text-dark border">{{ $c->language ?? 'English' }}</small>
                @endif
            </div>
        </div>
    </a>
@endforeach
