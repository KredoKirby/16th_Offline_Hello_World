@extends('layouts.app')

@section('title', 'Home')

@section('content')
    {{-- ===== Up next (date & time unified) ===== --}}
    <section class="container py-4">
        <h2 class="h4 mb-3">Up next</h2>

        @if ($upNext)
            @php
                // 明示的にアプリのタイムゾーンで「その時刻がJSTの◯時」を作る（変換ではなく“解釈”）
                $tz = config('app.timezone', 'Asia/Tokyo');
                $dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $upNext->date . ' ' . $upNext->time, $tz);

                $duration = $upNext->duration_minutes ?? 50;
                $end = (clone $dt)->addMinutes($duration);

                $course = $upNext->course->title ?? 'Course Name';
                $topic = $upNext->topic->name ?? 'Topic Name';
                $teacher = $upNext->teacher->name ?? 'Teacher';
                $iconUrl = $upNext->course->icon_url ?? asset('images/placeholder-course.png');

                $whenStr = $dt->format('D, M j H:i') . '–' . $end->format('H:i');
                $isToday = $dt->isToday();

                // JSに渡すのはUNIXエポック（ms）
                $startTsMs = $dt->getTimestamp() * 1000; // Carbon 2なら $dt->valueOf() でもOK
            @endphp

            <div class="card">
                <div class="card-body py-3 px-3">
                    <div class="d-flex align-items-center gap-3 flex-wrap">

                        {{-- 左：アイコン --}}
                        <img src="{{ $iconUrl }}" alt="" class="rounded-3 border flex-shrink-0"
                            style="width:48px;height:48px;object-fit:cover;">

                        {{-- 中央：タイトル（1行）＋メタ（1行） --}}
                        <div class="min-w-0 flex-grow-1">
                            <div class="fw-semibold fs-5 text-truncate">
                                {{ $course }} <span class="text-body-secondary">・</span> {{ $topic }}
                            </div>

                            <div class="d-flex align-items-center flex-wrap gap-2 mt-1 text-secondary small">
                                {{-- とき（日時を一体化） --}}
                                <span class="d-inline-flex align-items-center">
                                    <i class="fa-regular fa-calendar me-1"></i>{{ $whenStr }}
                                    @if ($isToday)
                                        <span class="badge text-bg-success ms-2">Today</span>
                                    @endif
                                </span>

                                <span>•</span>

                                {{-- Teacher --}}
                                <span>with <span class="text-body">{{ $teacher }}</span></span>

                                <span>•</span>

                                {{-- カウントダウン --}}
                                <span id="upnext-countdown" class="badge text-bg-secondary" aria-live="polite"
                                    data-start-ts="{{ $startTsMs }}"></span>
                            </div>
                        </div>

                        {{-- Right: actions --}}
                        <div class="d-flex gap-2 ms-auto">
                            <button type="button" class="btn btn-primary btn-sm px-3">Enter</button>

                            {{-- Cancel (DELETE with confirmation) --}}
                            <form method="POST" action="{{ route('students.bookings.cancel', $upNext->id) }}"
                                onsubmit="return confirm('Cancel this booking?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3">Cancel</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-light border d-flex align-items-center justify-content-between py-2 px-3 mb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-regular fa-calendar-plus text-secondary"></i>
                    <span>No upcoming bookings</span>
                </div>
            </div>
        @endif
    </section>

    @push('scripts')
        <script>
            (function() {
                const el = document.getElementById('upnext-countdown');
                if (!el) return;

                // ← 文字列ISOではなく、ミリ秒の数値を使う
                const startAtMs = Number(el.dataset.startTs);

                function render() {
                    const diff = startAtMs - Date.now(); // どのTZでも正しい差分になる

                    if (diff <= -60 * 1000) {
                        el.textContent = 'Live';
                        el.classList.remove('text-bg-secondary');
                        el.classList.add('text-bg-danger');
                        return;
                    }
                    if (diff <= 0) {
                        el.textContent = 'Starting';
                        el.classList.remove('text-bg-secondary');
                        el.classList.add('text-bg-warning');
                        return;
                    }

                    const sec = Math.floor(diff / 1000);
                    const d = Math.floor(sec / 86400);
                    const h = Math.floor((sec % 86400) / 3600);
                    const m = Math.floor((sec % 3600) / 60);

                    let txt = 'in ';
                    if (d) txt += d + 'd ';
                    if (h) txt += h + 'h ';
                    txt += m + 'm';
                    el.textContent = txt.trim();
                }

                render();
                setInterval(render, 60 * 1000);
            })();
        </script>
    @endpush
    <section class="container py-4">
        <div class="row g-3">
            <!-- Book a class -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Book a class</h2>
                        <form method="POST" action="{{ route('students.bookings.store') }}" id="bookingForm">
                            @csrf

                            <div class="row">
                                {{-- Course --}}
                                <div class="mb-3 col-6">
                                    <label for="course_id" class="form-label fw-semibold">Course</label>
                                    <select name="course_id" id="course_id" class="form-select" required>
                                        <option value="" disabled {{ old('course_id') ? '' : 'selected' }}>Choose a
                                            course
                                        </option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}"
                                                {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Topic --}}
                                <div class="mb-3 col-6">
                                    <label for="topic_id" class="form-label fw-semibold">Topic</label>
                                    <select class="form-select" name="topic_id" id="topic_id" required disabled>
                                        <option value="" disabled selected>Select a topic</option>
                                    </select>
                                    <div id="topicHelp" class="form-text d-none">Next topic set automatically.
                                    </div>
                                </div>


                            </div>

                            {{-- Date（空きスロットの日付） --}}
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="date" class="form-label fw-semibold">Date</label>
                                    <select class="form-select" id="date" required disabled>
                                        <option value="" disabled selected>Select a date</option>
                                    </select>
                                    {{-- ▼ 空きスロットが無い場合のメッセージ --}}
                                    <div id="noSlotMessage" class="form-text text-danger d-none fw-semibold">
                                        No available slots for this course.
                                    </div>
                                </div>

                                {{-- Time（選んだ日付のスロット + 教師名） --}}
                                <div class="col-6 mb-3">
                                    <label for="time" class="form-label fw-semibold">Time</label>
                                    <select class="form-select" id="time" required disabled>
                                        <option value="" disabled selected>Select a time</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                {{-- ▼ 先生選択アクション（常に表示） --}}
                                <div id="teacherActions" class="mt-2">
                                    <label class="form-label fw-semibold d-block mb-2">Teacher</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        {{-- デフォルトで "Automatically assigned" 状態 --}}
                                        <button type="button" id="btnRandom" class="btn btn-primary text-white">
                                            Automatically assigned
                                        </button>
                                        <button type="button" id="btnChoose" class="btn btn-outline-primary">
                                            <span id="btnChooseText">Choose a teacher</span>
                                            <span class="badge text-bg-primary ms-1" id="teacherCount">0</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- 空き先生ゼロのときの赤文字 --}}
                            <div id="noTeacherMsg" class="form-text text-danger d-none fw-semibold">
                                No teacher is available for that date/time.
                            </div>

                            {{-- 送信するのは booking_id / course_id / topic_id / teacher_id --}}
                            <input type="hidden" name="booking_id" id="booking_id">
                            <input type="hidden" name="teacher_id" id="teacher_id">

                            <button type="submit" class="btn btn-primary w-100 mt-4">Book a class</button>
                        </form>

                        {{-- ▼ 先生一覧モーダル（Bootstrap） --}}
                        <div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        {{-- タイトルに選択中のDate/Timeを反映します --}}
                                        <h5 class="modal-title">
                                            Choose a teacher
                                            <small class="text-muted d-block" id="modalSubtitle"></small>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul id="teacherList" class="list-group">
                                            {{-- JS で <li> を注入 --}}
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <small class="text-muted">Select a teacher to confirm your slot.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            (function() {
                                const courseSel = document.getElementById('course_id');
                                const topicSel = document.getElementById('topic_id');
                                const topicHelp = document.getElementById('topicHelp');
                                const dateSel = document.getElementById('date');
                                const timeSel = document.getElementById('time');

                                const bidInput = document.getElementById('booking_id'); // ← DBに送る booking_id
                                const tidInput = document.getElementById('teacher_id'); // ← DBに送る teacher_id（手動選択時のみ）

                                const teacherActions = document.getElementById('teacherActions');
                                const teacherCountEl = document.getElementById('teacherCount');
                                const btnRandom = document.getElementById('btnRandom');
                                const btnChoose = document.getElementById('btnChoose');
                                const noTeacherMsg = document.getElementById('noTeacherMsg');
                                const teacherList = document.getElementById('teacherList');
                                const modalSubtitle = document.getElementById('modalSubtitle');

                                let slots = []; // API から取得 [{booking_id, date, time, teacher_id, teacher_name}, ...]
                                let currentTeachers = []; // 現在の date/time に空いている先生の枠
                                let selectedTeacherId = null; // 手動で選んだ teacher（date/time 変更で必ず破棄する）

                                // --- UI ヘルパー ---
                                function resetSelect(sel, ph) {
                                    sel.innerHTML = '';
                                    const opt = document.createElement('option');
                                    opt.value = '';
                                    opt.textContent = ph;
                                    opt.disabled = true;
                                    opt.selected = true;
                                    sel.appendChild(opt);
                                    sel.disabled = true;
                                }

                                function enable(sel) {
                                    sel.disabled = false;
                                }

                                function setUIRandomSelected(_name = null, count = 0) {
                                    btnRandom.classList.remove('btn-outline-primary', 'disabled');
                                    btnRandom.classList.add('btn-primary', 'text-white');
                                    btnRandom.textContent = 'Automatically assigned';

                                    btnChoose.classList.remove('btn-primary');
                                    btnChoose.classList.add('btn-outline-primary');
                                    document.getElementById('btnChooseText').textContent = 'Choose a teacher';

                                    teacherCountEl.classList.remove('d-none');
                                    teacherCountEl.textContent = String(count);
                                }

                                function setUITeacherSelected(teacherName) {
                                    btnRandom.classList.remove('btn-primary', 'text-white');
                                    btnRandom.classList.add('btn-outline-primary');
                                    btnRandom.textContent = 'Automatically assigned';

                                    btnChoose.classList.remove('btn-outline-primary');
                                    btnChoose.classList.add('btn-primary');
                                    document.getElementById('btnChooseText').textContent = `${teacherName} assigned`;

                                    teacherCountEl.classList.add('d-none');
                                }

                                // 重要：手動選択や以前の booking_id を完全クリアして「自動割当」モードに戻す
                                function clearTeacherSelectionToAuto() {
                                    setUIRandomSelected(null, currentTeachers.length || 0);
                                    selectedTeacherId = null;
                                    tidInput.value = '';
                                    bidInput.value = ''; // ← これが残っていると「以前の date/time の枠」で送信される
                                }

                                // --- モーダルのリスト描画（手動選択用） ---
                                function renderTeacherList(list) {
                                    teacherList.innerHTML = '';
                                    list.forEach(item => {
                                        const li = document.createElement('li');
                                        li.className = 'list-group-item d-flex align-items-center justify-content-between';
                                        li.innerHTML = `
        <div><div class="fw-semibold">${item.teacher_name}</div></div>
        <button type="button" class="btn btn-sm btn-primary">Select</button>
      `;
                                        li.querySelector('button').addEventListener('click', function() {
                                            // 手動選択に切替
                                            selectedTeacherId = item.teacher_id;
                                            tidInput.value = item.teacher_id;

                                            const selectedDate = dateSel.value;
                                            const selectedTime = timeSel.value;

                                            const slot = slots.find(s =>
                                                s.teacher_id === item.teacher_id &&
                                                s.date === selectedDate &&
                                                s.time === selectedTime
                                            );

                                            if (slot) {
                                                bidInput.value = slot.booking_id;
                                                setUITeacherSelected(item.teacher_name);
                                            } else {
                                                // 今の date/time にその先生の空きが無ければ自動に戻す
                                                clearTeacherSelectionToAuto();
                                                const choices = slots.filter(s => s.date === selectedDate && s.time ===
                                                    selectedTime);
                                                if (choices.length) {
                                                    const rnd = choices[Math.floor(Math.random() * choices.length)];
                                                    bidInput.value = rnd.booking_id;
                                                    tidInput.value = rnd.teacher_id;
                                                    setUIRandomSelected(rnd.teacher_name, choices.length);
                                                } else {
                                                    bidInput.value = '';
                                                    tidInput.value = '';
                                                    noTeacherMsg.classList.remove('d-none');
                                                }
                                            }

                                            // モーダルを閉じる
                                            const modalEl = document.getElementById('teacherModal');
                                            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                            modal.hide();
                                            modalEl.addEventListener('hidden.bs.modal', () => {
                                                document.body.classList.remove('modal-open');
                                                document.querySelectorAll('.modal-backdrop').forEach(el => el
                                                    .remove());
                                            }, {
                                                once: true
                                            });
                                        });
                                        teacherList.appendChild(li);
                                    });
                                }

                                // --- Choose 押下：date/time が未選択なら開かない ---
                                btnChoose.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    if (!dateSel.value || !timeSel.value) {
                                        alert('Please select a date and time first.');
                                        return;
                                    }
                                    document.body.classList.remove('modal-open');
                                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                    const modalEl = document.getElementById('teacherModal');
                                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                                    modal.show();
                                });

                                // --- Random（自動割当）押下：現在の currentTeachers からランダム確定 ---
                                btnRandom.addEventListener('click', function() {
                                    if (!currentTeachers.length) {
                                        noTeacherMsg.classList.remove('d-none');
                                        return;
                                    }
                                    const pick = currentTeachers[Math.floor(Math.random() * currentTeachers.length)];
                                    bidInput.value = pick.booking_id;
                                    tidInput.value = pick.teacher_id;
                                    selectedTeacherId = null; // 自動割当なので手動選択はクリア
                                    setUIRandomSelected(pick.teacher_name, currentTeachers.length);
                                });

                                // ========== ハンドラ（関数）を名前付きで用意して、都度 remove → add できるように ==========
                                function onDateChange() {
                                    // date を変えたら、必ず手動選択を外して自動に戻す（＆過去の booking_id を消す）
                                    resetSelect(timeSel, 'Select a time');
                                    currentTeachers = [];
                                    clearTeacherSelectionToAuto();

                                    noTeacherMsg.classList.add('d-none');
                                    teacherList.innerHTML = '';
                                    teacherCountEl.textContent = '0';

                                    const list = slots.filter(s => s.date === dateSel.value);
                                    const uniqueTimes = [...new Set(list.map(s => s.time))];
                                    uniqueTimes.forEach(t => {
                                        const o = document.createElement('option');
                                        o.value = t;
                                        o.textContent = t;
                                        timeSel.appendChild(o);
                                    });
                                    if (uniqueTimes.length) enable(timeSel);
                                }

                                function onTimeChange() {
                                    // time を変えたら、必ず「自動割当」に戻し、この date/time の中からランダム確定
                                    clearTeacherSelectionToAuto();

                                    const selectedDate = dateSel.value;
                                    const selectedTime = timeSel.value;

                                    currentTeachers = slots.filter(s => s.date === selectedDate && s.time === selectedTime);
                                    modalSubtitle.textContent = `${selectedDate} ${selectedTime}`;

                                    if (currentTeachers.length > 0) {
                                        teacherCountEl.textContent = String(currentTeachers.length);
                                        noTeacherMsg.classList.add('d-none');
                                        renderTeacherList(currentTeachers); // 手動選択用リストは表示（ただしデフォは自動）

                                        const slot = currentTeachers[Math.floor(Math.random() * currentTeachers.length)];
                                        bidInput.value = slot.booking_id;
                                        tidInput.value = slot.teacher_id; // 自動でも teacher_id は送る（要件次第で空でも可）
                                        setUIRandomSelected(slot.teacher_name, currentTeachers.length);
                                    } else {
                                        teacherCountEl.textContent = '0';
                                        noTeacherMsg.classList.remove('d-none');
                                        teacherList.innerHTML = '';
                                    }
                                }

                                // --- course 変更時：全リセット → API 取込み → date セレクト再構築 ---
                                courseSel.addEventListener('change', async function() {
                                    resetSelect(topicSel, 'Select a topic');
                                    resetSelect(dateSel, 'Select a date');
                                    resetSelect(timeSel, 'Select a time');

                                    // 過去 state を完全クリア
                                    slots = [];
                                    currentTeachers = [];
                                    clearTeacherSelectionToAuto();

                                    topicHelp.classList.add('d-none');
                                    noTeacherMsg.classList.add('d-none');
                                    teacherList.innerHTML = '';
                                    teacherCountEl.textContent = '0';

                                    // 既存の change ハンドラを掃除（多重バインド防止）
                                    dateSel.removeEventListener('change', onDateChange);
                                    timeSel.removeEventListener('change', onTimeChange);

                                    const cid = this.value;
                                    if (!cid) return;

                                    const resp = await fetch(`/students/api/courses/${cid}/init`, {
                                        headers: {
                                            'Accept': 'application/json'
                                        }
                                    });
                                    if (!resp.ok) {
                                        resetSelect(topicSel, 'Failed to load');
                                        return;
                                    }
                                    const data = await resp.json();

                                    // topics
                                    if (Array.isArray(data.topics)) {
                                        data.topics.forEach(t => {
                                            const o = document.createElement('option');
                                            o.value = t.id;
                                            o.textContent = t.name;
                                            topicSel.appendChild(o);
                                        });
                                        enable(topicSel);
                                    }
                                    if (data.suggested) {
                                        const found = [...topicSel.options].find(o => Number(o.value) === Number(data
                                            .suggested));
                                        if (found) {
                                            found.selected = true;
                                            topicHelp.classList.remove('d-none');
                                        }
                                    }

                                    // slots
                                    slots = Array.isArray(data.slots) ? data.slots : [];

                                    // dates
                                    const dates = [...new Set(slots.map(s => s.date))];
                                    document.getElementById('noSlotMessage').classList.add('d-none');
                                    if (dates.length === 0) {
                                        document.getElementById('noSlotMessage').classList.remove('d-none');
                                        return;
                                    }

                                    dates.forEach(d => {
                                        const o = document.createElement('option');
                                        o.value = d;
                                        o.textContent = d;
                                        dateSel.appendChild(o);
                                    });
                                    if (dates.length) enable(dateSel);

                                    // ここで改めてハンドラをバインド
                                    dateSel.addEventListener('change', onDateChange);
                                    timeSel.addEventListener('change', onTimeChange);
                                });

                                // --- 送信前チェック：booking_id が空なら送信不可 ---
                                document.getElementById('bookingForm').addEventListener('submit', function(e) {
                                    if (!bidInput.value) {
                                        e.preventDefault();
                                        alert('Please select a date and time slot.');
                                    }
                                });
                            })();
                        </script>
                    </div>
                </div>
            </div>

            <!-- Calendar (frame only) -->
            <div class="col-lg-6">
                <div class="card h-100">
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
    {{-- ===== Lesson history (line-card with Details modal) ===== --}}
    <section class="container py-4">
        <h2 class="h4 mb-3">Lesson history</h2>

        <div class="vstack gap-3">
            @forelse ($history as $b)
                @php
                    $dt = \Carbon\Carbon::parse($b->date . ' ' . $b->time)->timezone(config('app.timezone'));
                    $duration = $b->duration_minutes ?? 50;
                    $end = (clone $dt)->addMinutes($duration);

                    $course = $b->course->title ?? 'Course';
                    $topic = $b->topic->name ?? 'Topic';
                    $teacher = $b->teacher->name ?? 'Teacher';
                    $iconUrl = $b->course->icon_url ?? asset('images/placeholder-course.png');

                    // Unified date & time (e.g., Wed, Oct 29 18:00–18:50)
                    $whenStr = $dt->format('D, M j H:i') . '–' . $end->format('H:i');

                    // Report fields (optional)
                    $status = $b->report->status ?? null;
                    $nextTop = $b->report->next_topic ?? '—';

                    // Badge color by status
                    $statusClass = match (strtolower((string) $status)) {
                        'done', 'completed' => 'text-bg-success',
                        'pending', 'todo' => 'text-bg-warning',
                        'missed', 'absent' => 'text-bg-danger',
                        default => 'text-bg-secondary',
                    };
                @endphp

                <div class="card">
                    <div class="card-body py-3 px-3">
                        <div class="d-flex align-items-center gap-3 flex-wrap">

                            {{-- Left: course icon --}}
                            <img src="{{ $iconUrl }}" alt="Course icon" class="rounded-3 border flex-shrink-0"
                                style="width:48px;height:48px;object-fit:cover;">

                            {{-- Middle: title + meta --}}
                            <div class="min-w-0 flex-grow-1">
                                <div class="fw-semibold fs-5 text-truncate">
                                    {{ $course }} <span class="text-body-secondary">·</span> {{ $topic }}
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2 mt-1 text-secondary small">
                                    <span class="d-inline-flex align-items-center">
                                        <i class="fa-regular fa-calendar me-1"></i>{{ $whenStr }}
                                    </span>
                                    <span>•</span>
                                    <span>with <span class="text-body">{{ $teacher }}</span></span>
                                </div>
                            </div>

                            {{-- Right: Details button (opens modal) --}}
                            <div class="d-flex gap-2 ms-auto">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                                    data-bs-toggle="modal" data-bs-target="#bookingDetails-{{ $b->id }}">
                                    Details
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Modal (one per item) --}}
                <div class="modal fade" id="bookingDetails-{{ $b->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lesson details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Booking --}}
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-regular fa-calendar-check text-primary"></i>
                                        <span class="text-uppercase text-muted small fw-semibold">Booking</span>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-clone"></i><span>Course</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $course }}">{{ $course }}</div>
                                            </div>
                                        </li>

                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-bookmark"></i><span>Topic</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $topic }}">{{ $topic }}</div>
                                            </div>
                                        </li>

                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-user"></i><span>Teacher</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $teacher }}">{{ $teacher }}</div>
                                            </div>
                                        </li>

                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-clock"></i><span>Date & time</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $whenStr }}">{{ $whenStr }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                {{-- Report --}}
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-regular fa-clipboard text-primary"></i>
                                        <span class="text-uppercase text-muted small fw-semibold">Report</span>
                                    </div>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-flag"></i><span>Status</span>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="badge {{ $statusClass }}">{{ $status ?? '—' }}</span>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="list-group-item px-0">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6 text-secondary small d-flex align-items-center gap-2">
                                                    <i class="fa-regular fa-lightbulb"></i><span>Next topic</span>
                                                </div>
                                                <div class="col-6 fw-semibold text-end text-truncate"
                                                    title="{{ $nextTop }}">{{ $nextTop }}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light border"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border d-flex align-items-center gap-2 mb-0">
                    <i class="fa-regular fa-circle-info text-secondary"></i>
                    <span class="small">No history yet.</span>
                </div>
            @endforelse
        </div>

        {{-- View more --}}
        <div class="text-center mt-3">
            <a href="{{ route('students.lessonhistory') }}" class="btn btn-light border">
                View more
            </a>
        </div>
    </section>
@endsection
