<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Dashboard Overview" />
    </x-slot>

    <div class="dashboard-container">
        <x-ui.welcome-message 
            name="{{ explode(' ', Auth::user()->name)[0] }}" 
            subtitle="Here's what's happening with your classes today." 
        />

        <div class="stats-grid">
            <x-ui.stat-card 
                title="Total Assignments" 
                value="{{ $stats['totalAssignments'] }}" 
                color="primary" 
                trendText="Live from database" 
                trendColor="success" 
                trendIcon="up">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zM12.75 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12zM9 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12z" clip-rule="evenodd" />
                      <path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>

            <x-ui.stat-card 
                title="Total Submissions" 
                value="{{ $stats['totalSubmissions'] }}" 
                color="success" 
                trendText="All time" 
                trendColor="success" 
                trendIcon="up">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>

            <x-ui.stat-card 
                title="Pending / Late" 
                value="{{ $stats['pendingLate'] }}" 
                color="warning" 
                trendText="Action required" 
                trendColor="warning" 
                trendIcon="none">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot>
            </x-ui.stat-card>
        </div>

        {{-- ========== LATEST SUBMISSIONS ========== --}}
        <div class="dashboard-section" style="margin-top: var(--spacing-lg);">
            <div class="dashboard-section-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:var(--spacing-md);">
                <h2 style="font-size: var(--font-size-xl); font-weight: 700; color: var(--color-text);">Recent Submissions</h2>
                <a href="{{ route('teacher.submissions.history') }}" class="btn btn-secondary btn-sm">View All History</a>
            </div>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Assignment</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissionsPreview as $submission)
                            <tr>
                                <td style="font-weight: 600;">{{ $submission['studentName'] }}</td>
                                <td>{{ $submission['subjectName'] }}</td>
                                <td>{{ $submission['taskTitle'] }}</td>
                                <td class="text-sm color-muted">{{ $submission['submittedAt'] }}</td>
                                <td>
                                    <x-ui.badge 
                                        variant="{{ $submission['status'] === 'checked' ? 'success' : 'warning' }}" 
                                        label="{{ ucfirst($submission['status']) }}" 
                                    />
                                    @if($submission['isLate'])
                                        <x-ui.badge variant="danger" label="Late" />
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('teacher.submissions.show', $submission['id']) }}" class="btn btn-ghost btn-sm">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="data-table-empty">No recent submissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
