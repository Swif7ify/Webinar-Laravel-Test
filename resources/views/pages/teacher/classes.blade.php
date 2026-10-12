<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="My Classes" />
    </x-slot>

    <div class="classes-page-container">

        {{-- ========== HEADER ========== --}}
        <div class="classes-page-header">
            <div>
                <h2 class="classes-page-title">Active Classes</h2>
                <p class="classes-page-subtitle">
                    Manage and view all your current classes
                    <span class="classes-count-badge">{{ $classCount }} / 3 classes</span>
                </p>
            </div>
            <div class="classes-page-actions">
                @if($classCount < 3)
                    <x-ui.button
                        variant="primary"
                        id="btn-open-create-class"
                        x-on:click="$dispatch('open-modal', 'create-class-modal')"
                    >
                        Create Class
                    </x-ui.button>
                @else
                    <x-ui.button variant="secondary" id="btn-create-class-disabled" disabled>
                        Class Limit Reached
                    </x-ui.button>
                @endif
            </div>
        </div>

        {{-- ========== SEARCH ========== --}}
        <div class="classes-search">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="classes-search-icon">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input type="text" placeholder="Search classes..." id="classes-search-input" />
        </div>

        {{-- ========== CLASS GRID ========== --}}
        <div class="classes-grid" id="classes-grid">
            @forelse($classes as $class)
                <x-ui.class-card
                    class-code="{{ $class->code }}"
                    title="{{ $class->subject->name }}"
                    schedule="{{ $class->schedule_day }} {{ $class->schedule_time }}"
                    teacher-name="{{ Auth::user()->name }}"
                    link="{{ route('teacher.classes.show', $class->id) }}"
                />
            @empty
                <div class="classes-empty-state" id="classes-empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="classes-empty-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                    <p class="classes-empty-text">You have no active classes yet.</p>
                    <p class="classes-empty-sub">Click <strong>Create Class</strong> to get started.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ========== MODALS ========== --}}
    <x-ui.modal id="create-class-modal" title="Create New Class">
        <form action="{{ route('teacher.classes.store') }}" method="POST" id="form-create-class" class="create-class-form">
            @csrf

            <div class="create-class-code-block">
                <span class="create-class-code-label">Class Code</span>
                <div class="create-class-code-display">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="create-class-code-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                    </svg>
                    <span class="create-class-code-value">{{ $generatedCode }}</span>
                    <span class="create-class-code-badge">Auto-generated</span>
                </div>
                <input type="hidden" name="class_code" value="{{ $generatedCode }}">
            </div>

            <x-forms.select
                name="subject_id"
                label="Subject"
                required
                id="select-subject-id"
                :options="$subjects->pluck('name', 'id')->toArray()"
            />

            <div class="create-class-schedule-row">
                <x-forms.select
                    name="schedule_day"
                    label="Day"
                    required
                    id="select-schedule-day"
                    :options="[
                        'Monday'    => 'Monday',
                        'Tuesday'   => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday'  => 'Thursday',
                        'Friday'    => 'Friday',
                        'Saturday'  => 'Saturday',
                    ]"
                />
                <div class="create-class-time-inputs">
                    <x-forms.input
                        name="start_time"
                        label="Start"
                        type="time"
                        required
                        id="input-start-time"
                    />
                    <x-forms.input
                        name="end_time"
                        label="End"
                        type="time"
                        required
                        id="input-end-time"
                    />
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <x-ui.button
                variant="danger"
                id="btn-cancel-create-class"
                x-on:click="$dispatch('close-modal', 'create-class-modal')"
            >
                Cancel
            </x-ui.button>
            <x-ui.button
                variant="primary"
                id="btn-submit-create-class"
                type="button"
                disabled
                x-on:click="document.getElementById('form-create-class').submit()"
            >
                Create Class
            </x-ui.button>
        </x-slot>
    </x-ui.modal>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const takenSubjectIds = @json($takenSubjectIds->values());
            const takenSchedules  = @json($takenSchedules->values());

            const subjectSelect = document.getElementById('select-subject-id');
            const daySelect     = document.getElementById('select-schedule-day');
            const startTime     = document.getElementById('input-start-time');
            const endTime       = document.getElementById('input-end-time');
            const submitBtn     = document.getElementById('btn-submit-create-class');

            function formatTimeDisplay(timeStr) {
                if (!timeStr) return '';
                const [hours, minutes] = timeStr.split(':');
                const h = parseInt(hours, 10);
                const ampm = h >= 12 ? 'PM' : 'AM';
                const h12 = h % 12 || 12;
                return `${h12}:${minutes} ${ampm}`;
            }

            function syncSubmitState() {
                const allFilled = subjectSelect.value !== '' &&
                                  daySelect.value      !== '' &&
                                  startTime.value      !== '' &&
                                  endTime.value        !== '';
                submitBtn.disabled = !allFilled;
            }

            function checkScheduleConflict() {
                const day   = daySelect.value;
                const start = startTime.value;
                const end   = endTime.value;

                if (!day || !start || !end) return;

                const displayTime = `${formatTimeDisplay(start)} - ${formatTimeDisplay(end)}`;

                const conflict = takenSchedules.some(
                    s => s.day === day && s.time === displayTime
                );

                if (conflict) {
                    window.useToast().warning(`You already have a class on ${day} at ${displayTime}.`);
                    daySelect.value = '';
                    startTime.value = '';
                    endTime.value   = '';
                    syncSubmitState();
                }

                if (start >= end) {
                    window.useToast().error('End time must be after start time.');
                    endTime.value = '';
                    syncSubmitState();
                }
            }

            subjectSelect.addEventListener('change', () => {
                const selectedId = parseInt(subjectSelect.value, 10);
                if (takenSubjectIds.includes(selectedId)) {
                    window.useToast().warning('You already have a class for this subject.');
                    subjectSelect.value = '';
                }
                syncSubmitState();
            });

            daySelect.addEventListener('change', () => {
                syncSubmitState();
                checkScheduleConflict();
            });

            startTime.addEventListener('change', () => {
                syncSubmitState();
                checkScheduleConflict();
            });

            endTime.addEventListener('change', () => {
                syncSubmitState();
                checkScheduleConflict();
            });

            syncSubmitState();
        });
    </script>

</x-app-layout>