<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Assignments" />
    </x-slot>

    <div class="my-assignments-page">

        {{-- ========== METRICS ========== --}}
        <div class="assignments-metrics">
            <div class="assignment-metric">
                <span class="assignment-metric__value">{{ $metrics['totalActive'] }}</span>
                <span class="assignment-metric__label">Total Active</span>
            </div>
            <div class="assignment-metric">
                <span class="assignment-metric__value">{{ $metrics['dueThisWeek'] }}</span>
                <span class="assignment-metric__label">Due This Week</span>
            </div>
            <div class="assignment-metric">
                <span class="assignment-metric__value">{{ $metrics['overdue'] }}</span>
                <span class="assignment-metric__label">Overdue</span>
            </div>
            <div class="assignment-metric">
                <span class="assignment-metric__value">{{ $metrics['completed'] }}</span>
                <span class="assignment-metric__label">Completed</span>
            </div>
        </div>

        {{-- ========== CONTROLS ========== --}}
        <div class="assignments-controls">
            <div class="assignments-filters" id="assignments-filters">
                <button class="filter-pill filter-pill--active" data-filter="all">All Assignments</button>
                <button class="filter-pill" data-filter="active">Active</button>
                <button class="filter-pill" data-filter="completed">Completed</button>
                <button class="filter-pill" data-filter="overdue">Overdue</button>
            </div>
            <div class="assignments-search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="assignments-search-input" placeholder="Search assignments..." />
            </div>
        </div>

        {{-- ========== TASK LIST ========== --}}
        <div class="assignments-list">
            @forelse($tasks as $task)
                <article class="assignment-card assignment-card--{{ $task->statusClass }} assignment-card--tone-{{ $task->pillClass }}" data-status="{{ $task->statusClass }}" data-title="{{ strtolower($task->title) }}">
                    <div class="assignment-card__content">
                        <div class="assignment-card__header">
                            <div class="assignment-card__badges">
                                <span class="assignment-state-pill assignment-state-pill--{{ $task->pillClass }}">{{ $task->pillLabel }}</span>
                                <span class="assignment-card__course">{{ $task->courseLabel }}</span>
                            </div>
                        </div>

                        <div class="assignment-card__body">
                            <h3 class="assignment-card__title">{{ $task->title }}</h3>
                            <p class="assignment-card__desc">{{ $task->descriptionText }}</p>
                        </div>

                        <div class="assignment-card__footer">
                            <div class="assignment-card__meta">
                                <div class="assignment-meta-item {{ $task->statusClass === 'overdue' ? 'assignment-meta-item--urgent' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $task->due_date->format('M d, Y \a\t g:i A') }}
                                </div>
                                <div class="assignment-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                    </svg>
                                    {{ $task->attachmentLabel }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="assignment-card__actions">
                        <x-ui.button variant="secondary" class="assignment-action-btn" href="{{ route('student.assignments.show', $task->id) }}">View Details</x-ui.button>
                    </div>
                </article>
            @empty
                <div class="assignments-empty">
                    <p>No assignments found.</p>
                </div>
            @endforelse
        </div>

        @if($tasks->hasPages())
            <div class="assignments-pagination mt-6 flex justify-center">
                {{ $tasks->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
