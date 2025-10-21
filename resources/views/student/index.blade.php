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
                        <form method="POST" action="{{ route('students.bookings.store') }}">
                            @csrf

                            {{-- Course --}}
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select name="course_id" id="course_id" class="form-select" required>
                                    <option value="" disabled {{ old('course_id') ? '' : 'selected' }}>Choose a course
                                    </option>
                                    @forelse($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No enrolled courses found.</option>
                                    @endforelse
                                </select>
                            </div>

                            {{-- Topic (dependent on Course) --}}
                            <div class="mb-3">
                                <label for="topic_id" class="form-label">Topic</label>
                                <select class="form-select" name="topic_id" id="topic_id"
                                    {{ old('course_id') ? '' : 'disabled' }} required>
                                    <option value="" disabled {{ old('topic_id') ? '' : 'selected' }}>Select a topic
                                    </option>
                                    {{-- Ajax でコース選択後に埋める --}}
                                </select>
                                <div id="topicHelp" class="form-text d-none">The next topic has been automatically selected.
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ old('date') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Time</label>
                                    <select class="form-select" id="time" name="time" required>
                                        @for ($h = 0; $h < 24; $h++)
                                            @php $t = sprintf('%02d:00', $h); @endphp
                                            <option value="{{ $t }}" {{ old('time') === $t ? 'selected' : '' }}>
                                                {{ $t }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-5">
                                Book a class
                            </button>
                        </form>

                        {{-- === Ajax: dependent dropdown === --}}
                        {{-- APIは /students/api/courses/{course}/topics （TopicApiController@byCourse） --}}
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <script>
                            (function() {
                                const courseSelect = document.getElementById('course_id');
                                const topicSelect = document.getElementById('topic_id');
                                const topicHelp = document.getElementById('topicHelp');
                                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const oldCourseId = "{{ old('course_id') }}";
                                const oldTopicId = "{{ old('topic_id') }}";

                                // 選択肢を初期化
                                function resetTopicSelect(placeholder = 'Select a topic') {
                                    topicSelect.innerHTML = '';
                                    const opt = document.createElement('option');
                                    opt.value = '';
                                    opt.textContent = placeholder;
                                    opt.disabled = true;
                                    opt.selected = true;
                                    topicSelect.appendChild(opt);
                                }

                                // リストから option を作成
                                function populateTopics(list) {
                                    resetTopicSelect();
                                    if (!Array.isArray(list) || list.length === 0) {
                                        const none = document.createElement('option');
                                        none.value = '';
                                        none.textContent = 'No topics available for this course.';
                                        none.disabled = true;
                                        topicSelect.appendChild(none);
                                        topicSelect.disabled = true;
                                        return;
                                    }
                                    list.forEach(item => {
                                        const opt = document.createElement('option');
                                        opt.value = item.id;
                                        // API は {id, name} を返す想定（titleならここを item.title に）
                                        opt.textContent = item.name;
                                        topicSelect.appendChild(opt);
                                    });
                                    topicSelect.disabled = false;
                                }

                                // 指定IDを選択
                                function selectTopicById(id) {
                                    const found = Array.from(topicSelect.options).find(o => Number(o.value) === Number(id));
                                    if (found) found.selected = true;
                                }

                                // コース変更でAPI呼び出し
                                courseSelect.addEventListener('change', async function() {
                                    const cid = this.value;
                                    topicHelp.classList.add('d-none');
                                    if (!cid) {
                                        topicSelect.disabled = true;
                                        resetTopicSelect();
                                        return;
                                    }

                                    try {
                                        const res = await fetch(`/students/api/courses/${cid}/topics`, {
                                            headers: {
                                                'X-CSRF-TOKEN': csrf,
                                                'Accept': 'application/json'
                                            }
                                        });
                                        if (!res.ok) throw new Error('Failed to load topics');
                                        const data = await res.json(); // { topics: [{id,name}], suggested: <id|null> }

                                        populateTopics(data.topics);

                                        // 優先順位: old('topic_id') → suggested → （そのまま）
                                        if (oldTopicId) {
                                            selectTopicById(oldTopicId);
                                        } else if (data.suggested) {
                                            selectTopicById(data.suggested);
                                            topicHelp.classList.remove('d-none');
                                        }
                                    } catch (e) {
                                        console.error(e);
                                        resetTopicSelect('Failed to load topics');
                                        topicSelect.disabled = true;
                                    }
                                });

                                // 初期表示：old('course_id') があれば自動ロード
                                if (oldCourseId) {
                                    courseSelect.value = oldCourseId;
                                    const evt = new Event('change');
                                    courseSelect.dispatchEvent(evt);
                                } else {
                                    topicSelect.disabled = true;
                                    resetTopicSelect();
                                }

                                // コース未選択でトピックを開くのを防止
                                topicSelect.addEventListener('mousedown', function(e) {
                                    if (!courseSelect.value) {
                                        e.preventDefault();
                                        alert('Please select a course first.');
                                    }
                                });
                            })();

                            // dateとtimeを現在の日時より前のものは選択できないようにする。
                            document.addEventListener('DOMContentLoaded', function() {
                                const dateInput = document.getElementById('date');
                                const timeSelect = document.getElementById('time');

                                const now = new Date();
                                const today = now.toISOString().split('T')[0];
                                dateInput.min = today;

                                // 現在時刻＋1時間を計算
                                let nextHour = now.getHours() + 1;
                                let defaultDate = today;

                                if (nextHour >= 24) {
                                    // 23時台なら翌日の00:00に
                                    nextHour = 0;
                                    const tomorrow = new Date(now);
                                    tomorrow.setDate(now.getDate() + 1);
                                    defaultDate = tomorrow.toISOString().split('T')[0];
                                }

                                // 日付をデフォルト値に設定
                                dateInput.value = defaultDate;

                                // 時間を"HH:00"にフォーマットして初期選択
                                const formatted = ('0' + nextHour).slice(-2) + ':00';
                                Array.from(timeSelect.options).forEach(opt => {
                                    opt.selected = (opt.value === formatted);
                                });

                                // === 今日の日付のとき、現在+1時間より前の時間を選択不可にする ===
                                function updateTimeOptions() {
                                    const selectedDate = new Date(dateInput.value);
                                    const isToday = selectedDate.toDateString() === now.toDateString();
                                    const currentHour = now.getHours() + 1;

                                    Array.from(timeSelect.options).forEach(opt => {
                                        const hour = parseInt(opt.value.split(':')[0], 10);
                                        opt.disabled = isToday && hour < currentHour;
                                    });
                                }

                                // 初期化時に実行
                                updateTimeOptions();

                                // 日付変更時に再評価
                                dateInput.addEventListener('change', updateTimeOptions);
                            });
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
