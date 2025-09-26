@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <section class="container py-4">
        <h2 class="h5 mb-3">Lesson history</h2>

        <div class="vstack gap-3">
            @for ($i = 1; $i <= 5; $i++)
                <div class="card shadow-sm rounded-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <!-- 左側：ロゴ + テキスト -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white border rounded-3 d-flex align-items-center justify-content-center shadow-sm"
                                style="width:58px;height:58px;">
                                {{-- ロゴ画像（差し替えてください） --}}
                                <img src="#" alt="HTML5" class="img-fluid"
                                    style="width:42px;height:42px;object-fit:contain;">
                            </div>
                            <div>
                                <div class="fw-semibold">Course Name {{ $i }},&nbsp;Topic Name
                                    {{ $i }}</div>
                                <div class="small text-muted">Teacher Name {{ $i }}</div>
                                <div class="small text-muted">Sep {{ 10 + $i }}, 11:00–12:00</div>
                            </div>
                        </div>

                        <!-- 右側：ボタン -->
                        <a href="#" class="btn btn-info rounded-pill px-4 fw-semibold shadow-sm">
                            View Details
                        </a>
                    </div>
                </div>
            @endfor
        </div>

        <!-- ページネーション -->
        <nav class="mt-3">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
            </ul>
        </nav>
    </section>

@endsection
