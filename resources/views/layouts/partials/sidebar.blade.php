@php
    // あなたのDB仕様に合わせて調整してください
    // 例: 1=student, 2=teacher, 3=admin
    $roleId = Auth::user()->role_id ?? null;

    $isStudent = (string)$roleId === '1';
    $isTeacher = (string)$roleId === '2';
    $isAdmin   = (string)$roleId === '3';
@endphp

@if($isStudent)
    {{-- 生徒用サイドバー（添付デザイン準拠） --}}
    <div class="p-4 rounded-3 border border-primary shadow-sm" style="background:#B7E3E4;">
        {{-- ロゴ --}}
        <div class="text-center mb-4">
            {{-- 画像を public/images/hello-world.png に配置してください --}}
            <img src="{{ asset('images/hello-world.png') }}" alt="HELLO WORLD"
                 class="img-fluid rounded-circle"
                 style="width:140px;height:140px;object-fit:cover;">
        </div>

        {{-- メインメニュー --}}
        <nav class="mb-5">
            <ul class="list-unstyled fw-bold" style="font-size:1.6rem; line-height:1.9;">
                {{-- ルート名は環境に合わせて差し替え（未定なら # のままでOK） --}}
                <li class="mb-3"><a class="text-decoration-none text-dark" href="#">Home</a></li>
                <li class="mb-3"><a class="text-decoration-none text-dark" href="#">Courses</a></li>
                <li class="mb-3"><a class="text-decoration-none text-dark" href="#">Self-learning</a></li>
                <li class="mb-3"><a class="text-decoration-none text-dark" href="#">Forum</a></li>
            </ul>
        </nav>

        {{-- ユーザー領域 --}}
        <div class="mb-4">
            <div class="h4 fw-bold mb-3">{{ Auth::user()->name ?? 'Username' }}</div>
            <ul class="list-unstyled" style="font-size:1.35rem;">
                <li class="mb-2"><a class="text-decoration-none text-dark" href="#">Profile</a></li>
                <li class="mb-2"><a class="text-decoration-none text-dark" href="#">My learning</a></li>
                <li class="mb-4"><a class="text-decoration-none text-dark" href="#">Lesson History</a></li>
            </ul>

            {{-- Logout（赤） --}}
            <a href="{{ route('logout') }}"
               class="fw-bold text-danger text-decoration-none"
               style="font-size:1.6rem;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

@elseif($isTeacher)
    {{-- TODO: 先生用サイドバー（後で実装） --}}

@elseif($isAdmin)
    {{-- TODO: 管理者用サイドバー（後で実装） --}}

@else
    {{-- role不明時は何も表示しない（必要ならデフォルトを用意） --}}
@endif