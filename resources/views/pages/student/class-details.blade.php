<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Class Details" />
    </x-slot>

    <div class="class-details-container">
        <div class="class-details-banner">
            <div class="class-details-banner__content">
                <h1 class="class-details-banner__title">{{ $schoolClass->subject->name }}</h1>
                <div class="class-details-banner__meta">
                    <div class="class-details-banner__info-item">
                        Classcode: {{ $schoolClass->code }}
                    </div>
                    <div class="class-details-banner__info-item">
                        {{ $schoolClass->schedule_day }} {{ $schoolClass->schedule_time }}
                    </div>
                    <div class="class-details-banner__info-item">
                        Instructor: {{ $schoolClass->teacher->name }}
                    </div>
                </div>
            </div>
        </div>

        <section class="class-details-section">
            <h2 class="class-details-section__title">Assignments & Tasks</h2>

            <div class="assignments-list">
                @forelse ($tasks as $task)
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
                            <x-ui.button variant="secondary" class="assignment-action-btn" href="{{ route('student.classes.tasks.show', ['class' => $schoolClass->id, 'task' => $task->id]) }}">View Details</x-ui.button>
                        </div>
                    </article>
                @empty
                    <x-ui.empty-state
                        message="No assignments have been posted for this class yet."
                        icon="heroicon-o-clipboard-document-list"
                    />
                @endforelse
            </div>

            @if($tasks->hasPages())
                <div class="assignments-pagination mt-6 flex justify-center">
                    {{ $tasks->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
