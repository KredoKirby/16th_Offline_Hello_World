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
                                    <label for="course_id" class="form-label">Course</label>
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
                                    <label for="topic_id" class="form-label">Topic</label>
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
                                    <label for="date" class="form-label">Date</label>
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
                                    <label for="time" class="form-label">Time</label>
                                    <select class="form-select" id="time" required disabled>
                                        <option value="" disabled selected>Select a time</option>
                                    </select>
                                </div>
                            </div>
                            {{-- ▼ 先生選択アクション（Timeの下に追加） --}}
                            <div id="teacherActions" class="d-none mt-2">
                                <div class="d-flex gap-2">
                                    <button type="button" id="btnRandom" class="btn btn-outline-primary btn-sm">
                                        Random assign
                                    </button>
                                    <button type="button" id="btnChoose" class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#teacherModal">
                                        Choose a teacher <span class="badge text-bg-secondary" id="teacherCount">0</span>
                                    </button>
                                </div>
                            </div>
                            {{-- 空き先生ゼロのときの赤文字 --}}
                            <div id="noTeacherMsg" class="form-text text-danger d-none fw-semibold">
                                No teacher is available for that date/time.
                            </div>

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

                            {{-- 送信するのは booking_id / course_id / topic_id --}}
                            <input type="hidden" name="booking_id" id="booking_id">

                            <button type="submit" class="btn btn-primary w-100 mt-4">Book a class</button>
                        </form>

                        <script>
                            (function() {
                                const courseSel = document.getElementById('course_id');
                                const topicSel = document.getElementById('topic_id');
                                const topicHelp = document.getElementById('topicHelp');
                                const dateSel = document.getElementById('date');
                                const timeSel = document.getElementById('time');
                                const bidInput = document.getElementById('booking_id');

                                // ▼ 追加：先生関連UI
                                const teacherActions = document.getElementById('teacherActions');
                                const teacherCountEl = document.getElementById('teacherCount');
                                const btnRandom = document.getElementById('btnRandom');
                                const btnChoose = document.getElementById('btnChoose');
                                const noTeacherMsg = document.getElementById('noTeacherMsg');
                                const teacherList = document.getElementById('teacherList');
                                const modalSubtitle = document.getElementById('modalSubtitle');

                                let slots = []; // APIから来る [{booking_id,date,time,teacher_id,teacher_name},...]
                                let currentTeachers = []; // 選択中date/timeで空いている先生スロット

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

                                // ▼ モーダルの先生一覧を描画
                                function renderTeacherList(list) {
                                    teacherList.innerHTML = '';
                                    list.forEach(item => {
                                        const li = document.createElement('li');
                                        li.className = 'list-group-item d-flex align-items-center justify-content-between';
                                        li.innerHTML = `
        <div>
          <div class="fw-semibold">${item.teacher_name}</div>
          <div class="small text-muted">${item.date} ${item.time}</div>
        </div>
        <button type="button" class="btn btn-sm btn-primary">Select</button>
      `;
                                        // Select クリック → booking_id をセット → モーダルを閉じる
                                        li.querySelector('button').addEventListener('click', function() {
                                            bidInput.value = item.booking_id;
                                            const modalEl = document.getElementById('teacherModal');
                                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(
                                                modalEl);
                                            modal.hide();
                                        });
                                        teacherList.appendChild(li);
                                    });
                                }

                                // ▼ Random assign
                                btnRandom?.addEventListener('click', function() {
                                    if (!currentTeachers.length) {
                                        noTeacherMsg.classList.remove('d-none');
                                        return;
                                    }
                                    const pick = currentTeachers[Math.floor(Math.random() * currentTeachers.length)];
                                    bidInput.value = pick.booking_id;

                                    // 軽いフィードバック
                                    btnRandom.classList.add('disabled');
                                    setTimeout(() => btnRandom.classList.remove('disabled'), 400);
                                });

                                // ▼ コース変更： topics / suggested / slots をまとめて取得
                                courseSel.addEventListener('change', async function() {
                                    resetSelect(topicSel, 'Select a topic');
                                    resetSelect(dateSel, 'Select a date');
                                    resetSelect(timeSel, 'Select a time');
                                    bidInput.value = '';
                                    topicHelp.classList.add('d-none');

                                    teacherActions.classList.add('d-none');
                                    noTeacherMsg.classList.add('d-none');
                                    teacherList.innerHTML = '';
                                    teacherCountEl.textContent = '0';

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

                                    // 1) Topic を反映
                                    if (Array.isArray(data.topics)) {
                                        data.topics.forEach(t => {
                                            const o = document.createElement('option');
                                            o.value = t.id;
                                            o.textContent = t.name; // titleなら t.title
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

                                    // 2) スロット（= teacher_course の先生に限定済み）
                                    slots = Array.isArray(data.slots) ? data.slots : [];

                                    // unique 日付
                                    const dates = [...new Set(slots.map(s => s.date))];

                                    // まず非表示に戻す
                                    document.getElementById('noSlotMessage').classList.add('d-none'); // ★ 追加

                                    // ★ 空きスロットが1件もない → 赤メッセージ表示して終了
                                    if (dates.length === 0) { // ★ 追加
                                        document.getElementById('noSlotMessage').classList.remove('d-none'); // ★ 追加
                                        return; // ★ 追加
                                    }

                                    dates.forEach(d => {
                                        const o = document.createElement('option');
                                        o.value = d;
                                        o.textContent = d;
                                        dateSel.appendChild(o);
                                    });
                                    if (dates.length) enable(dateSel);
                                    else {
                                        // 空なら Date/Time は使えない＆注意表示（必要なら出す）
                                        // ここでは特に何も出さず、time選択時のガードに任せます
                                    }

                                    // 日付選択でその日の time を展開
                                    dateSel.addEventListener('change', function() {
                                        resetSelect(timeSel, 'Select a time');
                                        bidInput.value = '';

                                        teacherActions.classList.add('d-none');
                                        noTeacherMsg.classList.add('d-none');
                                        teacherList.innerHTML = '';
                                        teacherCountEl.textContent = '0';

                                        const list = slots.filter(s => s.date === this.value);
                                        list.forEach(s => {
                                            const o = document.createElement('option');
                                            o.value = s.booking_id; // value は booking_id
                                            o.textContent = `${s.time}`; // 表示は HH:MM（教師名は先生選択で表示）
                                            timeSel.appendChild(o);
                                        });
                                        if (list.length) enable(timeSel);
                                    }, {
                                        once: true
                                    });
                                });

                                // ▼ Time 選択で：該当 date/time の空き先生を集計→ボタン表示→モーダル準備
timeSel.addEventListener('change', function() {
    // 1) booking_id を hidden に（既存挙動）
    bidInput.value = this.value || '';

    // 2) 選択中 date/time
    const selectedDate = dateSel.value;
    const selectedTimeText = this.options[this.selectedIndex]?.textContent?.trim(); // "HH:MM"

    // 3) teacher_course 由来の slots の中から date/time 完全一致の空き先生だけを抽出
    currentTeachers = slots.filter(s => s.date === selectedDate && s.time === selectedTimeText);

    // 4) モーダルのサブタイトルに反映
    modalSubtitle.textContent = `${selectedDate} ${selectedTimeText}`;

    // 5) UI 出し分け
    if (currentTeachers.length > 0) {
        teacherCountEl.textContent = String(currentTeachers.length);
        noTeacherMsg.classList.add('d-none');
        teacherActions.classList.remove('d-none');
        renderTeacherList(currentTeachers);

        // ★ デフォルトで random assign を自動実行（最初に選択状態にする）
        const pick = currentTeachers[Math.floor(Math.random() * currentTeachers.length)];
        bidInput.value = pick.booking_id;

        // Time セレクトの該当 option も選択済みにする
        const timeOptions = Array.from(timeSel.options);
        const targetOption = timeOptions.find(o => Number(o.value) === Number(pick.booking_id));
        if (targetOption) {
            targetOption.selected = true;
        }

        // Random assign ボタンの見た目を変更（"Assigned ✓"）
        btnRandom.classList.add('btn-success', 'text-white');
        btnRandom.textContent = 'Automatically assigned';
    } else {
        teacherActions.classList.add('d-none');
        teacherCountEl.textContent = '0';
        noTeacherMsg.classList.remove('d-none');
        teacherList.innerHTML = '';
    }
});

                                // 送信前チェック（booking_idが入っているか）
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
