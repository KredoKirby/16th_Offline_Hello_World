@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="container py-4">
        <h1 class="h4 mb-3">Up next</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <div
                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                    <!-- 左：アイコン + テキスト -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-3"
                            style="width:56px;height:56px;">
                            <!-- 画像にしたい場合は下の<i>を<img>に変えてOK -->
                            <i class="fa-solid fa-code fa-lg text-secondary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">
                                Course Name, <span class="ms-1">Topic Name</span>
                            </div>
                            <div class="fw-semibold">
                                Sep 22 (Mon) 13:00~
                            </div>
                        </div>
                    </div>

                    <!-- 右：ボタン -->
                    <a href="#" class="btn btn-primary">
                        Enter classroom
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="container py-4">
        <div class="row g-3">
            <!-- Book a class -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Book a class</h2>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Course</label>
                                <select class="form-select">
                                    <option selected>Select a course</option>
                                    <option>Beginner Japanese</option>
                                    <option>Business English</option>
                                    <option>Web Development</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Topic</label>
                                    <select class="form-select">
                                        <option selected>Select a topic</option>
                                        <option>Beginner Japanese</option>
                                        <option>Business English</option>
                                        <option>Web Development</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Time</label>
                                    <select class="form-select" name="time" required>
                                        <option value="" selected disabled>Select time</option>
                                        <option>08:00</option>
                                        <option>09:00</option>
                                        <option>10:00</option>
                                        <option>11:00</option>
                                        <option>12:00</option>
                                        <option>13:00</option>
                                        <option>14:00</option>
                                        <option>15:00</option>
                                        <option>16:00</option>
                                        <option>17:00</option>
                                        <option>18:00</option>
                                        <option>19:00</option>
                                        <option>20:00</option>
                                        <option>21:00</option>
                                        <option>22:00</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary w-100 mt-5">
                                Book a class
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Calendar (frame only) -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h4 mb-0">Calendar</h2>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Month switcher">
                                <button type="button" class="btn btn-outline-secondary" disabled>
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" disabled>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="rounded-3 bg-light-subtle border d-flex align-items-center justify-content-center flex-fill"
                            style="min-height: 360px;">
                            <span class="text-muted small">Calendar area (placeholder)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container py-4">
        <h2 class="h4 mb-3">Lesson history</h2>

        <div class="vstack gap-3">
            @for ($i = 1; $i <= 3; $i++)
                <div class="card shadow-sm">
                    <div
                        class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-3"
                                style="width:48px;height:48px;">
                                <i class="fa-solid fa-book-open text-secondary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">
                                    Course Name {{ $i }}, <span class="ms-1">Topic Name
                                        {{ $i }}</span>
                                </div>
                                <div class="fw-semibold">
                                    Sep 10 11:00–12:00 （i: {{ $i }}）
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-primary">View Details {{ $i }}</a>
                    </div>
                </div>
            @endfor
        </div>

        <!-- View More -->
        <div class="text-center mt-3">
            <button type="button" class="btn btn-light border">View more</button>
        </div>
    </section>
@endsection
