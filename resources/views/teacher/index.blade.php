@extends('layouts.app')

@section('title', 'Weekly Calendar')

@section('content')
    <section class="container py-3">
        {{-- CSRF（fetch用） --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Bulk Delete toolbar --}}
        <div class="d-flex justify-content-end mb-3">
            <button id="btn-open-bulk-range" type="button" class="btn btn-outline-danger">
                Bulk delete
            </button>
        </div>

        {{-- Bulk delete modal --}}
        <div class="modal fade" id="bulkRangeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="bulk-range-form" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk delete Open slots (by range)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="bd-date">Date</label>
                            <input type="date" class="form-control" id="bd-date" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold" for="bd-from">From (hour)</label>
                                <select class="form-select" id="bd-from" required></select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" for="bd-to">To (hour)</label>
                                <select class="form-select" id="bd-to" required></select>
                            </div>
                        </div>

                        <div class="form-text mt-2">
                            Only <strong>Open</strong> slots (student_id is <code>NULL</code>) will be deleted. Booked slots
                            are excluded.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Report modal --}}
        <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form id="report-form" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="rpt-booking-id">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="small text-muted">Student</div>
                                <div id="rpt-student" class="fw-semibold">—</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Date</div>
                                <div id="rpt-date" class="fw-semibold">—</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Time</div>
                                <div id="rpt-time" class="fw-semibold">—</div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="small text-muted">Course</div>
                                <div id="rpt-course" class="fw-semibold">—</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Topic</div>
                                <div id="rpt-topic" class="fw-semibold">—</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="rpt-status-select" class="form-label fw-semibold">Status</label>
                            <select id="rpt-status-select" class="form-select" required>
                                <option value="">—</option>
                                {{-- <option value="scheduled">scheduled</option> --}}
                                <option value="attended">attended</option>
                                <option value="absent">absent</option>
                                {{-- <option value="canceled by student">canceled by student</option> --}}
                                <option value="canceled by teacher">canceled by teacher</option> {{-- ★ 要望の選択肢 --}}
                                <option value="others">others</option> {{-- ★ 要望の選択肢 --}}
                            </select>
                        </div>

                        {{-- ★ Next topic（候補は id 昇順のリスト）。保存は「テキスト」で reports.next_topic に入れます --}}
                        <div class="mt-3">
                            <label for="rpt-next-topic" class="form-label fw-semibold">Next topic</label>
                            <select id="rpt-next-topic" class="form-select" required>
                                {{-- JS で id 昇順に埋め込む。値は「topic_name（タイトル）」をセット --}}
                            </select>
                            <div class="form-text">Default = current topic. Options are ordered by id asc.</div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold" for="rpt-feedback">Comment</label>
                            <textarea id="rpt-feedback" class="form-control" rows="4"
                                placeholder="Write feedback or the cancellation reason here..." required></textarea>
                            {{-- <div class="form-text">When teacher cancels, put the reason here.</div> --}}
                        </div>

                    </div>

                    <div class="modal-footer gap-2">
                        <button id="btn-save-report" type="button" class="btn btn-primary">Save report</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        {{-- <button id="btn-submit-cancel" type="button" class="btn btn-danger">Cancel booking</button> --}}
                    </div>
                </form>
            </div>
        </div>

        {{-- カレンダー --}}
        <div id="teacherWeekCal" class="rounded-3 border shadow-sm" data-feed-url="{{ route('teachers.calendar.show') }}"
            data-store-url="{{ route('teachers.bookings.store') }}"
            data-destroy-url="{{ route('teachers.bookings.destroy', ['id' => '__ID__']) }}"
            data-bulkdel-url="{{ route('teachers.bookings.bulkDelete') }}"
            data-cancel-url="{{ route('teachers.bookings.cancel', ['id' => '__ID__']) }}"
            data-report-url="{{ route('teachers.reports.show', ['booking' => '__ID__']) }}" {{-- ★追加：取得 --}}
            data-report-update-url="{{ route('teachers.reports.update', ['booking' => '__ID__']) }}" {{-- ★追加：保存 --}}
            style="min-height: 500px;"></div>
    </section>
@endsection

@push('styles')
    {{-- ① FullCalendar の公式CSSを必ず先に --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #teacherWeekCal .fc a {
            color: inherit !important;
            text-decoration: none !important;
        }

        #teacherWeekCal .fc-col-header-cell-cushion,
        #teacherWeekCal .fc-timegrid-axis-cushion,
        #teacherWeekCal .fc-daygrid-day-number {
            color: #000 !important;
        }

        #teacherWeekCal .fc .fc-toolbar-title {
            color: #000;
            font-weight: 600;
        }

        #teacherWeekCal .fc .fc-button {
            color: #212529;
            background: #f8f9fa;
            border-color: rgba(0, 0, 0, .12);
        }

        #teacherWeekCal .fc .fc-button:hover {
            background: #e9ecef;
            border-color: rgba(0, 0, 0, .18);
        }

        #teacherWeekCal .fc .fc-scrollgrid {
            border-radius: .75rem;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .05);
        }

        #teacherWeekCal .fc-theme-standard td,
        #teacherWeekCal .fc-theme-standard th {
            border-color: rgba(0, 0, 0, .08);
        }

        #teacherWeekCal .fc-timegrid-slot-label {
            color: #6c757d;
        }

        /* 1時間の高さを統一（必要なら値を調整） */
        /* #teacherWeekCal .fc .fc-timegrid-slot,
                                                                                            #teacherWeekCal .fc .fc-timegrid-slot-lane{ height:6em; } */
        /* 1時間の高さをさらに大きく（例：7.5em） */
        /* #teacherWeekCal {
                                                                                          --fc-timegrid-slot-min-height: 7.5em;
                                                                                        } */
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('teacherWeekCal');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const feed = el.dataset.feedUrl;
            const store = el.dataset.storeUrl;
            const delTpl = el.dataset.destroyUrl; // .../__ID__
            const bulk = el.dataset.bulkdelUrl;
            const cancelTpl = el.dataset.cancelUrl; // .../__ID__/cancel

            const ONE_HOUR_MS = 60 * 60 * 1000;
            const nowLocal = () => new Date();

            const fmtYMD = d =>
                `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
            const fmtHms = d =>
                `${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}:00`;

            const hoursBetween = (start, end) => {
                const out = [];
                const cur = new Date(start.getTime());
                cur.setMinutes(0, 0, 0);
                while (cur < end) {
                    out.push(fmtHms(cur));
                    cur.setHours(cur.getHours() + 1);
                }
                return out;
            };

            const calendar = new FullCalendar.Calendar(el, {
                themeSystem: 'bootstrap5',
                // timeZone: 'local',
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay,dayGridMonth'
                },
                /* ここはそのまま（all-day を出さない） */
                allDaySlot: false,

                /* 赤線の表示 */
                nowIndicator: true,
                contentHeight: 750, // ← 自動に
                expandRows: true,
                slotMinTime: '06:00:00',
                slotMaxTime: '24:00:00',
                slotDuration: '01:00:00',
                snapDuration: '01:00:00',
                selectable: true,
                selectMirror: true,
                selectOverlap: false,
                slotLabelInterval: {
                    hours: 1
                },

                views: {
                    dayGridMonth: {
                        // ✅ Monthでも 24h 表記に統一（例: 03:00）
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        displayEventEnd: true
                    },
                    timeGridWeek: {
                        // 念のため明示（既に同設定なら省略可）
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }
                    },
                    timeGridDay: {
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }
                    }
                },

                // 過去開始は選べない
                // ✅ ここを置き換え：Month では選択自体を許可しない
                selectAllow(span) {
                    const isMonth = calendar.view?.type === 'dayGridMonth';
                    if (isMonth) return false; // ← 月表示では新規作成不可
                    return span.start.getTime() >= nowLocal().getTime();
                },

                // 複数枠作成
                select(info) {
                    const times = hoursBetween(info.start, info.end);
                    if (!times.length) return calendar.unselect();

                    fetch(store, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                date: fmtYMD(info.start),
                                times,
                                duration_minutes: 50
                            })
                        }).then(() => calendar.refetchEvents())
                        .finally(() => calendar.unselect());
                },

                // クリックで削除/取消
                eventClick(arg) {
                    const id = arg.event.id;
                    const isBooked = !!arg.event.extendedProps?.student_id;

                    if (!isBooked) {
                        // Open → 即削除
                        const url = delTpl.replace('__ID__', id);
                        if (!confirm('Delete this open slot?')) return;
                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(() => calendar.refetchEvents());
                    } else {
                        // Booked → Report モーダルを開く
                        openReportModal(id);
                    }
                },

                eventSources: [{
                    url: feed,
                    method: 'GET'
                }],

                eventDidMount(info) {
                    const start = info.event.start;
                    const end = info.event.end ?? new Date(start.getTime() + ONE_HOUR_MS);
                    const isPast = end.getTime() < Date.now();
                    const isBooked = !!info.event.extendedProps?.student_id;

                    const hasReport = info.event.extendedProps?.has_report === true;
                    const statusRaw = info.event.extendedProps?.report_status ?? '';
                    const status = typeof statusRaw === 'string' ? statusRaw.toLowerCase() : '';

                    const wrap = info.el;
                    const main = info.el.querySelector('.fc-event-main');
                    if (main) {
                        main.style.setProperty('background', 'transparent', 'important');
                        main.style.setProperty('border', 'none', 'important');
                        main.style.setProperty('color', 'inherit', 'important');
                        main.style.padding = '0';
                    }

                    let bg, brd;

                    if (hasReport && status === 'canceled by teacher') {
                        // ★ 講師キャンセルは赤
                        bg = '#dc3545'; // Bootstrap danger
                        brd = '1px solid #bb2d3b';
                    } else if (hasReport) {
                        // ★ レポートあり（通常）は緑
                        bg = '#198754'; // Bootstrap success
                        brd = '1px solid #157347';
                    } else if (isPast) {
                        bg = 'rgba(108,117,125,1)'; // gray for past
                        brd = '1px solid rgba(108,117,125,.9)';
                    } else if (isBooked) {
                        bg = '#d63384'; // booked (no report yet): pink
                        brd = '1px solid #c12d76';
                    } else {
                        bg = '#0d6efd'; // open: blue
                        brd = '1px solid rgba(13,110,253,.9)';
                    }

                    wrap.style.setProperty('background', bg, 'important');
                    wrap.style.setProperty('border', brd, 'important');
                    wrap.style.borderRadius = '.5rem';
                    wrap.style.padding = '.12rem .32rem';
                    wrap.style.fontWeight = '600';
                    wrap.style.setProperty('color', '#fff', 'important');
                    wrap.style.cursor = 'pointer';
                },

                eventContent(arg) {
                    const timeTxt = (arg.timeText || '').replace(/\s*-\s*/g, '–');
                    const wrap = document.createElement('div');
                    wrap.style.display = 'flex';
                    wrap.style.alignItems = 'center';
                    wrap.style.gap = '6px';
                    wrap.style.lineHeight = '1.1';

                    const time = document.createElement('span');
                    time.textContent = timeTxt;
                    time.style.fontWeight = '700';
                    time.style.fontSize = '.85rem';

                    const label = document.createElement('span');
                    const isBooked = !!arg.event.extendedProps?.student_id;
                    const reportStatus = arg.event.extendedProps?.report_status ?? null;
                    const title = arg.event.title || ''; // 学生名などが入っている想定

                    // ★ ここを変更：reportがあれば "Booked" は出さない
                    let labelText;
                    if (reportStatus) {
                        // 学生名があれば「学生名 · status」、なければ「status」のみ
                        labelText = `${reportStatus}`;
                    } else {
                        // reportなし：学生名があればそれ、無ければ Booked/Open
                        labelText = (isBooked ? 'Booked' : 'Open');
                    }

                    label.textContent = labelText;
                    label.style.fontWeight = '600';
                    label.style.fontSize = '.85rem';

                    wrap.appendChild(time);
                    wrap.appendChild(label);
                    return {
                        domNodes: [wrap]
                    };
                }
            });
            calendar.render();

            // ==== Bulk delete (by range) ====
            const bulkUrl = el.dataset.bulkdelUrl;

            // Bootstrap Modal（CDNでBootstrap使っている前提）
            const bulkModalEl = document.getElementById('bulkRangeModal');
            const bulkModal = bulkModalEl ? new bootstrap.Modal(bulkModalEl) : null;
            const btnOpenBulk = document.getElementById('btn-open-bulk-range');
            const bulkForm = document.getElementById('bulk-range-form');

            btnOpenBulk?.addEventListener('click', () => {
                // 今日を初期値に
                const d = new Date();
                const ymd =
                    `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                document.getElementById('bd-date').value = ymd;
                bulkModal?.show();
            });

            bulkForm?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const date = document.getElementById('bd-date').value;
                const from = document.getElementById('bd-from').value;
                const to = document.getElementById('bd-to').value;
                if (!date || !from || !to) return;

                if (!confirm(`Delete OPEN slots on ${date} from ${from} to ${to}?`)) return;

                try {
                    const res = await fetch(bulkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            date,
                            from,
                            to
                        })
                    });

                    if (!res.ok) {
                        const j = await res.json().catch(() => ({}));
                        console.warn('Bulk delete error', j);
                        alert('Deletion failed. Please check your inputs.');
                        return;
                    }

                    bulkModal?.hide();
                    calendar.refetchEvents();
                } catch (err) {
                    console.error(err);
                    alert('A network error occurred.');
                }
            });
            (function setupBulkDeleteControls() {
                // モーダルのIDは環境に合わせて変更（例: bulkDeleteModal）
                const modalEl = document.getElementById('bulkDeleteModal');
                const dateEl = document.getElementById('bd-date');
                const fromEl = document.getElementById('bd-from');
                const toEl = document.getElementById('bd-to');

                if (!dateEl || !fromEl || !toEl) return;

                const pad = n => String(n).padStart(2, '0');
                const todayYMD = () => {
                    const n = new Date();
                    return `${n.getFullYear()}-${pad(n.getMonth()+1)}-${pad(n.getDate())}`;
                };
                const nextWholeHour = () => {
                    const n = new Date();
                    return (n.getMinutes() > 0 || n.getSeconds() > 0) ? n.getHours() + 1 : n.getHours();
                };

                const makeOptions = (select, startH, endH, selectedH = null) => {
                    select.innerHTML = '';
                    for (let h = startH; h <= endH; h++) {
                        const val = `${pad(h)}:00`;
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = val;
                        if (selectedH !== null && h === selectedH) opt.selected = true;
                        select.appendChild(opt);
                    }
                };

                function refresh() {
                    // 今日なら From の最小は「次の丸めた時」かつ 06:00 以上
                    const isToday = dateEl.value === todayYMD();
                    const minFromHour = Math.max(6, Math.min(nextWholeHour(), 24));

                    // From: 06..24（ただし今日なら min 未満を disabled）
                    makeOptions(fromEl, 6, 24);
                    if (isToday) {
                        [...fromEl.options].forEach(o => {
                            const h = parseInt(o.value.slice(0, 2), 10);
                            o.disabled = h < minFromHour;
                        });
                        // デフォルトを許容最小に合わせる
                        const defH = Math.min(Math.max(minFromHour, 6), 24);
                        fromEl.value = `${pad(defH)}:00`;
                    } else {
                        // 今日でなければ 06:00 を初期値
                        fromEl.value = '06:00';
                    }

                    // To: (From+1)..24
                    const fromH = parseInt(fromEl.value.slice(0, 2), 10) || 6;
                    const toStart = Math.min(fromH + 1, 24);
                    makeOptions(toEl, toStart, 24, toStart);
                }

                // From が変わったら To の下限を更新
                fromEl.addEventListener('change', () => {
                    const fromH = parseInt(fromEl.value.slice(0, 2), 10) || 6;
                    const toStart = Math.min(fromH + 1, 24);
                    makeOptions(toEl, toStart, 24, toStart);
                });

                // 日付が変わったら最小From再計算
                dateEl.addEventListener('change', refresh);

                // モーダルが開いたら初期化（Bootstrap 5）
                if (modalEl) {
                    modalEl.addEventListener('shown.bs.modal', () => {
                        if (!dateEl.value) dateEl.value = todayYMD();
                        refresh();
                    });
                } else {
                    // モーダルが無い(固定フォーム)環境でも初期化できるように
                    if (!dateEl.value) dateEl.value = todayYMD();
                    refresh();
                }
            })();

            // ==== Report modal (inside DOMContentLoaded) ====
            const reportTpl = el.dataset.reportUrl;
            const reportUpdateTpl = el.dataset.reportUpdateUrl;
            const reportModalEl = document.getElementById('reportModal');
            const reportModal = reportModalEl ? new bootstrap.Modal(reportModalEl) : null;

            async function openReportModal(bookingId) {
                if (!reportTpl || !reportModal) return;

                const url = reportTpl.replace('__ID__', bookingId);
                try {
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('Failed to fetch report');
                    const j = await res.json();

                    // 基本情報
                    document.getElementById('rpt-booking-id').value = j.booking?.id ?? bookingId;
                    document.getElementById('rpt-student').textContent = j.student?.name ?? '—';
                    document.getElementById('rpt-date').textContent = j.booking?.date ?? '—';
                    document.getElementById('rpt-time').textContent =
                        (j.booking?.start && j.booking?.end) ? `${j.booking.start} – ${j.booking.end}` : '—';
                    document.getElementById('rpt-course').textContent = j.course?.title ?? '—';
                    document.getElementById('rpt-topic').textContent = j.topic?.name ?? '—'; // ← 表示はname

                    // ステータス / フィードバック
                    document.getElementById('rpt-status-select').value = j.report?.status ?? '';
                    document.getElementById('rpt-feedback').value = j.report?.feedback ?? '';

                    // 次回トピックの選択肢（コースに紐づくもののみ）
                    const preferredId = j.preferred_topic_id ?? null;
                    fillNextTopicSelect(j.topics ?? [], preferredId);

                } catch (e) {
                    console.error(e);
                } finally {
                    reportModal.show();
                }
            }

            function fillNextTopicSelect(options, preferredId) {
  const sel = document.getElementById('rpt-next-topic');
  sel.innerHTML = '';

  // 先頭のプレースホルダ（必須化に効く）
  const ph = document.createElement('option');
  ph.value = '';
  ph.textContent = '— Select next topic —';
  ph.disabled = true; ph.selected = true; ph.hidden = true;
  sel.appendChild(ph);

  for (const t of options) {
    const opt = document.createElement('option');
    opt.value = String(t.id);           // 値はID
    opt.textContent = t.name ?? '';
    sel.appendChild(opt);
  }

  // もし初期選択させたいなら、ここでselectedをセット
  if (preferredId != null) {
    sel.value = String(preferredId);
  }
}

            const reportForm = document.getElementById('report-form');

document.getElementById('btn-save-report')?.addEventListener('click', async () => {
  // 1) HTML5バリデーション（一括）
  if (!reportForm.checkValidity()) {
    // 入力エラーのある項目をブラウザが強調表示
    reportForm.reportValidity();
    return;
  }

  // 2) 値の取得（必須なのでnullは入れない）
  const bookingId   = document.getElementById('rpt-booking-id').value;
  const status      = document.getElementById('rpt-status-select').value;
  const nextTopicId = parseInt(document.getElementById('rpt-next-topic').value, 10);
  const feedback    = document.getElementById('rpt-feedback').value.trim();

  if (!reportUpdateTpl || !bookingId) return;
  const url = reportUpdateTpl.replace('__ID__', bookingId);

  try {
    const res = await fetch(url, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        status,
        feedback,
        next_topic: nextTopicId
      })
    });

    if (!res.ok) {
      const j = await res.json().catch(() => ({}));
      console.warn('report update error', j);
      alert('Failed to save the report.');
      return;
    }

    reportModal.hide();
    calendar.refetchEvents();
  } catch (e) {
    console.error(e);
    alert('A network error occurred.');
  }
});

            // Cancel booking（キャンセル理由→学生通知＋report に反映はサーバ側で実装）
            document.getElementById('btn-submit-cancel')?.addEventListener('click', async () => {
                const bookingId = document.getElementById('rpt-booking-id').value;
                const reason = document.getElementById('rpt-cancel-reason').value.trim();
                if (!bookingId) return;
                if (!reason) {
                    alert('Please enter a reason.');
                    return;
                }

                const url = cancelTpl.replace('__ID__', bookingId);
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            reason
                        })
                    });
                    if (!res.ok) {
                        const j = await res.json().catch(() => ({}));
                        console.warn('cancel error', j);
                        alert('Cancel failed.');
                        return;
                    }
                    reportModal?.hide();
                    calendar.refetchEvents();
                } catch (e) {
                    console.error(e);
                    alert('Network error.');
                }
            });
        });
    </script>
@endpush
