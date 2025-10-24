@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="container py-4">

        {{-- 上部フォーム --}}
        <form class="row g-3 align-items-end mb-4" method="POST" action="{{ route('teachers.bookings.store') }}">
            @csrf
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label fw-semibold" for="date">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date"
                    required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label fw-semibold" for="time">Time</label>
                <select class="form-select @error('time') is-invalid @enderror" id="time" name="time" required>
                    @for ($h = 0; $h < 24; $h++)
                        @php $t = sprintf('%02d:00', $h); @endphp
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                @error('time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-4 col-md-3">
                <button type="submit" class="btn btn-primary w-100">Add schedule</button>
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
