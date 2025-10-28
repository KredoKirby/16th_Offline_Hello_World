@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="container py-4">
        <h1 class="h4 mb-3">Up next</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <div
                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-3"
                            style="width:56px;height:56px;">
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
