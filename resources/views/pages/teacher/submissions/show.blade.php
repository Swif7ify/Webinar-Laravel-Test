<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Check Submission" />
    </x-slot>

    <div class="grade-submission-container">
        {{-- ========== BREADCRUMBS ========== --}}
        <x-navigation.breadcrumbs :links="[
            ['label' => 'My Classes', 'url' => route('teacher.classes.index')],
            ['label' => $submission->task->schoolClass->code, 'url' => route('teacher.classes.show', $submission->task->class_id)],
            ['label' => 'Submissions', 'url' => route('teacher.tasks.submissions.index', $submission->task_id)],
            ['label' => $submission->student->name]
        ]" />

        <div class="grade-layout">
            {{-- ========== LEFT SIDE: SUBMISSION CONTENT ========== --}}
            <div class="grade-main">
                <div class="grade-card">
                    <div class="grade-card-header">
                        <h3 class="grade-card-title">Submitted Files</h3>
                        <div class="submission-meta">
                            Submitted {{ $submission->submitted_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="grade-card-body">
                        <div class="files-list">
                            @foreach($submission->files as $file)
                                <div class="file-item">
                                    <div class="file-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">{{ $file->original_name }}</div>
                                        <div class="file-size">{{ number_format($file->size_bytes / 1024, 1) }} KB</div>
                                    </div>
                                    <div class="file-actions">
                                        <a href="{{ route('files.download', $file->id) }}">
                                            <x-ui.button variant="secondary" size="sm">Download</x-ui.button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ========== TASK CONTEXT ========== --}}
                <div class="grade-card">
                    <div class="grade-card-header">
                        <h3 class="grade-card-title">Task Instructions</h3>
                    </div>
                    <div class="grade-card-body">
                        <div class="task-instructions prose">
                            @if($submission->task->instructions)
                                {!! $submission->task->instructions !!}
                            @else
                                <span class="text-muted">No instructions provided for this task.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== RIGHT SIDE: ACTIONS ========== --}}
            <div class="grade-sidebar">
                <div class="grade-card">
                    <div class="grade-card-header primary">
                        <h3 class="grade-card-title">Submission Review</h3>
                    </div>
                    <div class="grade-card-body">
                        <div class="submission-status-card">
                            <p class="status-label">Current Status</p>
                            <div class="status-badge-container">
                                <span class="status-badge status-{{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="form-actions" style="margin-top: var(--spacing-lg);">
                            @if($submission->status !== 'checked')
                                <x-ui.button type="button" id="btn-mark-checked" variant="primary" style="width: 100%;">
                                    Mark as Checked
                                </x-ui.button>
                            @else
                                <div class="checked-confirmation">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="checked-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>Checked on {{ $submission->graded_at->format('M d, Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="mark-checked-form" action="{{ route('teacher.submissions.mark-as-checked', $submission->id) }}" method="POST" style="display:none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('btn-mark-checked');
            if (btn) {
                btn.addEventListener('click', async () => {
                    const { isConfirmed } = await window.useConfirm().confirm({
                        title: 'Mark as Checked?',
                        text: 'This will notify the student that their submission has been reviewed and approved.',
                        icon: 'question',
                        confirmButtonText: 'Yes, mark as checked',
                        confirmButtonColor: 'var(--color-primary)',
                    });

                    if (isConfirmed) {
                        document.getElementById('mark-checked-form').submit();
                    }
                });
            }
        });
    </script>
</x-app-layout>
