<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Submissions: {{ $task->title }}" />
    </x-slot>

    <div class="submissions-container">
        <x-navigation.breadcrumbs :links="[
            ['label' => 'My Classes', 'url' => route('teacher.classes.index')],
            ['label' => $task->schoolClass->code, 'url' => route('teacher.classes.show', $task->class_id)],
            ['label' => 'Submissions']
        ]" />

        {{-- ========== TASK OVERVIEW CARD ========== --}}
        <div class="task-summary-card">
            <div class="task-summary-content">
                <div>
                    <span class="task-summary-subject">{{ $task->schoolClass->subject->name }}</span>
                    <h2 class="task-summary-title">{{ $task->title }}</h2>
                </div>
                <div class="task-summary-stats">
                    <div class="task-stat-item">
                        <span class="task-stat-value">{{ $submissions->count() }}</span>
                        <span class="task-stat-label">Submissions</span>
                    </div>
                    <div class="task-stat-item">
                        <span class="task-stat-value">{{ $submissions->where('status', 'checked')->count() }}</span>
                        <span class="task-stat-label">Checked</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== SUBMISSIONS TABLE ========== --}}
        <div class="submissions-list-card">
            <div class="card-header">
                <h3 class="card-title">Student Submissions</h3>
            </div>
            
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Review Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="student-name">{{ $submission->student->name }}</div>
                                            <div class="student-email">{{ $submission->student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($submission->status === 'checked')
                                        <x-ui.badge variant="success" label="Checked" />
                                    @else
                                        <x-ui.badge variant="warning" label="Pending" />
                                    @endif
                                </td>
                                <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    @if($submission->status === 'checked')
                                        <x-ui.badge variant="success" label="Checked" />
                                    @else
                                        <x-ui.badge variant="warning" label="Pending" />
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('teacher.submissions.show', $submission->id) }}">
                                        <x-ui.button variant="secondary" size="sm">
                                            View &amp; Check
                                        </x-ui.button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="data-table-empty">
                                    <x-ui.empty-state message="No submissions yet." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="pagination-container" style="padding: var(--spacing-md); border-top: 1px solid var(--color-border);">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .submissions-container {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .task-summary-card {
            background: var(--color-primary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            box-shadow: var(--shadow-md);
            color: var(--color-text-body);
        }

        .task-summary-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--spacing-lg);
        }

        .task-summary-subject {
            font-size: var(--font-size-xs);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.8);
        }

        .task-summary-title {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--color-bg-white);
            margin-top: var(--spacing-xs);
        }

        .task-summary-stats {
            display: flex;
            gap: var(--spacing-xl);
        }

        .task-stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .task-stat-value {
            font-size: var(--font-size-xl);
            font-weight: 700;
            color: var(--color-bg-white);
        }

        .task-stat-label {
            font-size: var(--font-size-xs);
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            text-transform: uppercase;
        }

        .submissions-list-card {
            background: var(--color-bg-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: var(--spacing-lg);
            border-bottom: 1px solid var(--color-border);
        }

        .card-title {
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: var(--color-text);
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--color-primary-subtle);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: var(--font-size-sm);
        }

        .student-name {
            font-weight: 600;
            color: var(--color-text);
            font-size: var(--font-size-sm);
        }

        .student-email {
            font-size: var(--font-size-xs);
            color: var(--color-text-muted);
        }

        .grade-value {
            font-weight: 700;
            color: var(--color-success);
        }

        .grade-max {
            font-size: var(--font-size-xs);
            color: var(--color-text-muted);
        }

        .grade-placeholder {
            font-size: var(--font-size-sm);
            color: var(--color-text-muted);
            font-style: italic;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</x-app-layout>
