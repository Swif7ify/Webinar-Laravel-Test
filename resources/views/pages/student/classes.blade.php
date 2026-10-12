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
                    View all your enrolled classes
                    <span class="classes-count-badge">{{ $classCount }} classes</span>
                </p>
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
                    teacher-name="{{ $class->teacher->name }}"
                    link="{{ route('student.classes.show', $class->id) }}"
                />
            @empty
                <div class="classes-empty-state" id="classes-empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="classes-empty-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                    <p class="classes-empty-text">You are not enrolled in any classes yet.</p>
                    <p class="classes-empty-sub">Ask your teacher to add you to a class.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>