@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="container py-4">

        {{-- 上部フォーム --}}
        <form class="row g-3 align-items-end mb-4">
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label fw-semibold">Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label fw-semibold">Time</label>
                <select class="form-select" name="time" required>
                    @for ($h = 0; $h < 24; $h++)
                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <button type="button" class="btn btn-primary w-100">Add schedule</button>
            </div>
        </form>

        {{-- Weekly Calendar：枠だけ（後で Google Calendar API を埋め込み） --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="rounded-3 border border-2 bg-light-subtle d-flex align-items-center justify-content-center"
                    style="min-height: 480px;">
                    <div class="text-center">
                        <div class="h5 mb-1">Weekly Calendar</div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
