<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Submission History" />
    </x-slot>

    <div class="history-container">
        {{-- ========== FILTERS ========== --}}
        <div class="history-filters-card">
            <form action="{{ route('teacher.submissions.history') }}" method="GET" class="history-filters-form">
                <div class="filter-group">
                    <label for="filter-student">Student</label>
                    <select name="student_id" id="filter-student" onchange="this.form.submit()">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ ($filters['student_id'] ?? '') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-subject">Subject</label>
                    <select name="subject_id" id="filter-subject" onchange="this.form.submit()">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ ($filters['subject_id'] ?? '') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-status">Submission Status</label>
                    <select name="status" id="filter-status" onchange="this.form.submit()">
                        <option value="">All Submissions</option>
                        <option value="ontime" {{ ($filters['status'] ?? '') == 'ontime' ? 'selected' : '' }}>On-time</option>
                        <option value="late" {{ ($filters['status'] ?? '') == 'late' ? 'selected' : '' }}>Late</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filter-grading">Review Status</label>
                    <select name="grading" id="filter-grading" onchange="this.form.submit()">
                        <option value="">All Review Status</option>
                        <option value="pending" {{ ($filters['grading'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="checked" {{ ($filters['grading'] ?? '') == 'checked' ? 'selected' : '' }}>Checked</option>
                    </select>
                </div>

                @if(!empty($filters['student_id']) || !empty($filters['subject_id']) || !empty($filters['status']) || !empty($filters['grading']))
                    <div class="filter-actions">
                        <a href="{{ route('teacher.submissions.history') }}" class="btn-reset-filters">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- ========== TABLE ========== --}}
        <div class="history-table-card">
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date Submitted</th>
                            <th>Student</th>
                            <th>Assignment</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Review</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            @php
                                $isLate = $submission->submitted_at->greaterThan($submission->task->due_date);
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="date-cell">
                                        <span class="date-primary">{{ $submission->submitted_at->format('M d, Y') }}</span>
                                        <span class="date-secondary">{{ $submission->submitted_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-cell">
                                        <div class="student-avatar">{{ substr($submission->student->name, 0, 1) }}</div>
                                        <span class="student-name">{{ $submission->student->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="task-title" title="{{ $submission->task->title }}">
                                        {{ Str::limit($submission->task->title, 30) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="subject-badge">{{ $submission->task->schoolClass->subject->name }}</span>
                                </td>
                                <td>
                                    @if($isLate)
                                        <x-ui.badge variant="danger" label="Late" />
                                    @else
                                        <x-ui.badge variant="success" label="On-time" />
                                    @endif
                                </td>
                                <td>
                                    @if($submission->status === 'checked')
                                        <x-ui.badge variant="success" label="Checked" />
                                    @else
                                        <x-ui.badge variant="warning" label="Pending" />
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('teacher.submissions.show', $submission->id) }}" class="btn-action-view">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="data-table-empty">
                                    <x-ui.empty-state message="No submissions found matching your filters." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="pagination-container">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
