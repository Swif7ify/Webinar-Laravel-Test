<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Class Details" />
    </x-slot>

    <div class="class-show-container">
        <x-navigation.breadcrumbs :links="[
        ['label' => 'My Classes', 'url' => route('teacher.classes.index')],
        ['label' => $schoolClass->code]
    ]" />

        {{-- ========== HEADER ========== --}}
        <div class="class-show-header">
            <div class="class-show-header-content">
                <div>
                    <h2 class="class-show-title">{{ $schoolClass->subject->name }}</h2>
                    <p class="class-show-meta">Class Code: {{ $schoolClass->code }} &bull;
                        {{ $schoolClass->schedule_day }} {{ $schoolClass->schedule_time }}
                    </p>
                </div>

                <div class="class-show-menu" x-data="{ open: false }" @click.outside="open = false">
                    <button class="class-show-menu-btn" id="btn-class-options" @click="open = !open"
                        :aria-expanded="open" aria-label="Class options">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                            class="class-show-menu-icon">
                            <circle cx="12" cy="5" r="1.5" />
                            <circle cx="12" cy="12" r="1.5" />
                            <circle cx="12" cy="19" r="1.5" />
                        </svg>
                    </button>

                    <div class="class-show-dropdown" x-show="open" x-transition>
                        <button class="class-show-dropdown-item" id="btn-edit-class">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit Class
                        </button>
                        <div class="class-show-dropdown-divider"></div>
                        <button class="class-show-dropdown-item class-show-dropdown-item--danger"
                            id="btn-archive-class">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Archive Class
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== TABS NAVIGATION ========== --}}
        <div class="class-tabs-nav" id="class-tabs">
            <button class="class-tab-btn active" data-tab-target="tab-students">Students</button>
            <button class="class-tab-btn" data-tab-target="tab-tasks">Tasks &amp; Assignments</button>
        </div>

        {{-- ========== TAB PANELS ========== --}}
        <div id="class-tab-panels">

            {{-- Students Tab --}}
            <div class="class-tab-panel active" id="tab-students" data-tab-content>
                <div class="tab-panel-header">
                    <h3 class="tab-panel-title">Enrolled Students</h3>
                    <x-ui.button variant="primary" id="btn-add-student">Add Student</x-ui.button>
                </div>

                <div class="data-table-container">
                    <table class="data-table" id="students-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Enrolled Date</th>
                            </tr>
                        </thead>
                        <tbody id="students-tbody">
                            @forelse($enrolledStudents as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->enrolled_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr id="students-empty-row">
                                    <td colspan="3" class="data-table-empty">No students enrolled yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($enrolledStudents->hasPages())
                    <div class="pagination-container" style="margin-top: var(--spacing-md);">
                        {{ $enrolledStudents->links() }}
                    </div>
                @endif
            </div>

            {{-- Tasks Tab --}}
            <div class="class-tab-panel" id="tab-tasks" data-tab-content>
                <div class="tab-panel-header">
                    <h3 class="tab-panel-title">Class Tasks</h3>
                    <x-ui.button variant="primary" id="btn-create-task">Create Task</x-ui.button>
                </div>

                <div class="task-card-list">
                    @forelse($tasks as $task)
                        <div class="task-card">
                            <div class="task-card-header">
                                <h4 class="task-card-title">{{ $task->title }}</h4>

                                <div class="relative" style="position: relative;" x-data="{ open: false }">
                                    <button type="button" @click="open = !open" @click.away="open = false"
                                        class="task-card-menu-btn" title="Options">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition.opacity
                                        style="display: none;" class="task-card-dropdown">
                                        <button type="button"
                                            class="task-card-dropdown-item task-card-dropdown-item--border task-card-btn-edit"
                                            data-task-id="{{ $task->id }}" data-task-title="{{ addslashes($task->title) }}"
                                            data-task-instructions="{{ addslashes($task->instructions) }}"
                                            data-task-due="{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y \\a\\t g:i A') }}"
                                            data-task-due-date="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}"
                                            data-task-due-time="{{ \Carbon\Carbon::parse($task->due_date)->format('H:i') }}"
                                            data-task-due-time-formatted="{{ \Carbon\Carbon::parse($task->due_date)->format('g:i A') }}"
                                            data-task-status="{{ $task->status }}"
                                            data-task-files="{{ json_encode($task->referenceFiles->map(fn($f) => ['name' => $f->original_name, 'size' => $f->size_bytes, 'path' => $f->stored_path])->values()) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.14l-2.668.9.9-2.668a4.5 4.5 0 011.14-1.89l12.548-12.549z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 7.125L16.875 4.5" />
                                            </svg>
                                            Edit Task
                                        </button>
                                        <button type="button"
                                            class="task-card-dropdown-item task-card-dropdown-item--danger task-card-btn-delete"
                                            data-task-id="{{ $task->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Delete Task
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="task-card-desc">
                                {{ Str::limit(strip_tags($task->instructions), 150) }}
                            </div>
                            <div class="task-card-footer">
                                <div class="task-card-meta">
                                    <div class="task-card-meta-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                    <div class="task-card-meta-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                        </svg>
                                        <span>{{ $task->referenceFiles->count() }}
                                            Attachment{{ $task->referenceFiles->count() !== 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                                <div class="task-card-actions">
                                    <button type="button" class="task-card-link-view task-card-btn-view"
                                        data-task-id="{{ $task->id }}" data-task-title="{{ addslashes($task->title) }}"
                                        data-task-instructions="{{ addslashes($task->instructions) }}"
                                        data-task-due="{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y \\a\\t g:i A') }}"
                                        data-task-due-time-formatted="{{ \Carbon\Carbon::parse($task->due_date)->format('g:i A') }}"
                                        data-task-files="{{ json_encode($task->referenceFiles->map(fn($f) => ['name' => $f->original_name, 'size' => $f->size_bytes, 'path' => $f->stored_path])->values()) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                        View Details
                                    </button>
                                    <a href="{{ route('teacher.tasks.submissions.index', $task->id) }}">
                                        <x-ui.button variant="primary" class="task-card-btn-submissions">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor"
                                                style="width:18px; height:18px; margin-right:6px;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                            </svg>
                                            Submissions
                                        </x-ui.button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state message="No tasks created yet." />
                    @endforelse
                </div>

                @if($tasks->hasPages())
                    <div class="pagination-container" style="margin-top: var(--spacing-md);">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ========== MODALS ========== --}}

    {{-- ========== TASK DETAILS MODAL ========== --}}
    <x-ui.modal id="task-details-modal" panelClass="task-details-modal-panel">
        <div class="task-details-single-col">
            {{-- Header --}}
            <div class="task-details-header">
                <div>
                    <h3 class="task-details-title" id="td-title"></h3>
                    <div class="task-details-subtitle">
                        <div class="task-details-subtitle-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            Due <span id="td-due-date"></span>
                        </div>
                        <div class="task-details-subtitle-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="td-due-time"></span>
                        </div>
                    </div>
                </div>
                <button type="button" x-on:click="$dispatch('close-modal', 'task-details-modal')"
                    class="task-details-close-btn" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Description --}}
            <div class="task-details-section">
                <h4 class="task-details-section-heading text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    TASK DESCRIPTION & INSTRUCTIONS
                </h4>
                <div class="task-details-instructions-box" id="td-instructions"></div>
            </div>

            {{-- Resources --}}
            <div class="task-details-section" id="td-resources-section">
                <div class="task-details-resources-header">
                    <h4 class="task-details-section-heading">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.415-6.415l10.584-10.585a2.536 2.536 0 113.585 3.586l-10.585 10.586a.536.536 0 01-.758-.758l10.585-10.586" />
                        </svg>
                        Shared Resources (<span id="td-resources-count">0</span>)
                    </h4>
                    <!-- Link logic handles clicking to download all (might require a custom JS route, we will hide it if no files) -->
                    <a href="#" class="task-details-download-all" id="td-download-all" style="display: none;">Download
                        All</a>
                </div>
                <div class="task-details-file-list" id="td-file-list"></div>
            </div>
        </div>
    </x-ui.modal>
    <x-ui.modal id="add-student-modal" title="Add Students">
        <div x-data="studentPicker()" x-init="init()">
            <div class="student-picker-toolbar">
                <p class="student-picker-count">
                    <span x-text="selectedIds.length"></span> selected
                </p>
                <button type="button" class="student-picker-select-all-btn" @click="toggleSelectAll()"
                    :disabled="filteredSelectableCount === 0" x-text="allFilteredSelected ? 'Clear All' : 'Select All'">
                </button>
            </div>

            <div class="student-picker-search">
                <input type="text" class="student-picker-search-input" id="student-search-input"
                    placeholder="Search by name or email…" x-model="search" autocomplete="off" />
            </div>

            <div class="student-picker-list" id="student-picker-list">
                <template x-for="student in filtered" :key="student.id">
                    <div class="student-picker-item" :class="{
                            'student-picker-item--enrolled': student.enrolled,
                            'student-picker-item--selected': isSelected(student.id) && !student.enrolled
                        }" @click="handleItemClick(student)">
                        <input type="checkbox" class="student-picker-checkbox"
                            :checked="isSelected(student.id) || student.enrolled" :disabled="student.enrolled"
                            @click.stop @change="toggleStudent(student)" />
                        <div class="student-picker-avatar" x-text="initials(student.name)"></div>
                        <div class="student-picker-info">
                            <div class="student-picker-name" x-text="student.name"></div>
                            <div class="student-picker-email" x-text="student.email"></div>
                        </div>
                        <template x-if="student.enrolled">
                            <span class="student-picker-enrolled-badge">Enrolled</span>
                        </template>
                    </div>
                </template>

                <template x-if="filtered.length === 0">
                    <div class="student-picker-empty">
                        <svg class="student-picker-empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span>No students found.</span>
                    </div>
                </template>
            </div>
        </div>

        <x-slot name="footer">
            <x-ui.button variant="danger" id="btn-cancel-add-student"
                x-on:click="$dispatch('close-modal', 'add-student-modal')">
                Cancel
            </x-ui.button>
            <x-ui.button variant="primary" id="btn-confirm-add-student"
                x-on:click="window.dispatchEvent(new CustomEvent('picker-submit'))">
                Add Selected
            </x-ui.button>
        </x-slot>
    </x-ui.modal>

    {{-- ========== CREATE TASK MODAL ========== --}}
    <x-ui.modal id="create-task-modal" title="Create Task" panelClass="task-modal-panel"
        titleId="create-task-modal-title">
        <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">

        <form id="create-task-form" method="POST" action="{{ route('teacher.classes.tasks.store', $schoolClass->id) }}"
            enctype="multipart/form-data" class="task-modal-form">
            @csrf
            <input type="hidden" name="_task_id" id="create-task-hidden-id" value="">


            {{-- ========== BASIC INFORMATION ========== --}}
            <div class="task-form-section">
                <h3 class="task-form-section-title">Basic Information</h3>

                <div class="task-form-field">
                    <label class="task-form-label" for="task-title">Assignment Title</label>
                    <input type="text" id="task-title" name="title" placeholder="e.g., Midterm Essay: AI Ethics"
                        autocomplete="off" />
                </div>

                <div class="task-form-field">
                    <label class="task-form-label">Course / Module</label>
                    <input type="text" value="{{ $schoolClass->subject->name }}" readonly tabindex="-1" />
                </div>

                <div class="task-form-field">
                    <label class="task-form-label">Instructions &amp; Description</label>
                    <div id="task-quill-editor" style="min-height:160px;"></div>
                    <textarea name="instructions" id="task-instructions-hidden" style="display:none;"></textarea>
                </div>
            </div>

            {{-- ========== SUBMISSION PARAMETERS ========== --}}
            <div class="task-form-section">
                <h3 class="task-form-section-title">Submission Parameters</h3>

                <div class="task-form-row">
                    <div class="task-form-field">
                        <label class="task-form-label" for="task-due-date">Due Date</label>
                        <input type="date" id="task-due-date" name="due_date" />
                    </div>
                    <div class="task-form-field">
                        <label class="task-form-label" for="task-due-time">Due Time</label>
                        <input type="time" id="task-due-time" name="due_time" />
                    </div>
                </div>
            </div>

            {{-- ========== REFERENCE MATERIALS ========== --}}
            <div class="task-form-section">
                <h3 class="task-form-section-title">Reference Materials</h3>
                <p class="task-form-label" style="margin:0 0 var(--spacing-sm);">Upload rubrics, datasets, or reading
                    materials for students.</p>

                <label class="file-drop-zone" id="task-drop-zone" for="task-file-input"
                    aria-label="Upload reference files">
                    <div class="file-drop-zone-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.338-2.32 5.75 5.75 0 011.023 8.625A4.5 4.5 0 0117.25 19.5H6.75z" />
                        </svg>
                    </div>
                    <p class="file-drop-title">Click to upload or drag and drop</p>
                    <p class="file-drop-subtitle">SVG, PNG, JPG or PDF (max. 10MB)</p>
                </label>
                <input type="file" name="reference_files[]" id="task-file-input" class="task-file-input" multiple
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png,.svg,.zip" style="display:none;" />

                <div class="ref-file-list" id="task-ref-file-list"></div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:var(--spacing-sm); padding-top:var(--spacing-sm);">
                <x-ui.button variant="danger" id="btn-cancel-create-task">
                    Cancel
                </x-ui.button>
                <x-ui.button variant="primary" type="submit" id="btn-submit-create-task" class="task-submit-btn">
                    <span id="create-task-submit-label">Create Task</span>
                </x-ui.button>
            </div>
        </form>

        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <script>
            (function () {
                function initQuill() {
                    if (window._quillInstance) return;
                    window._quillInstance = new Quill('#task-quill-editor', {
                        theme: 'snow',
                        placeholder: 'Write detailed instructions, grading criteria, and expectations here...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['link', 'image'],
                            ],
                        },
                    });

                    window._quillInstance.on('text-change', function () {
                        document.getElementById('task-instructions-hidden').value = window._quillInstance.root.innerHTML;
                    });
                }

                window.addEventListener('open-modal', function (e) {
                    if (e.detail === 'create-task-modal') {
                        setTimeout(initQuill, 50);
                    }
                });
            })();
        </script>
    </x-ui.modal>

    <form id="delete-task-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <form id="archive-class-form" method="POST" action="{{ route('teacher.classes.archive', $schoolClass->id) }}"
        style="display:none;">
        @csrf
        @method('PATCH')
    </form>

    <script>
        const CLASS_ID = {{ $schoolClass->id }};
        const ADD_STUDENTS_URL = '{{ route('teacher.classes.students.add', $schoolClass->id) }}';
        const STORE_TASK_URL = '{{ route('teacher.classes.tasks.store', $schoolClass->id) }}';
        const UPDATE_TASK_BASE = '{{ url('teacher/classes/' . $schoolClass->id . '/tasks') }}';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const ALL_STUDENTS = @json($modalStudents->values());

        function formatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function studentPicker() {
            return {
                students: [],
                search: '',
                selectedIds: [],
                submitting: false,

                init() {
                    this.students = ALL_STUDENTS;
                    window.addEventListener('picker-submit', () => this.submitStudents());
                },

                get selectableFiltered() {
                    return this.filtered.filter(student => !student.enrolled);
                },

                get filteredSelectableCount() {
                    return this.selectableFiltered.length;
                },

                get allFilteredSelected() {
                    return this.filteredSelectableCount > 0 && this.selectableFiltered.every(student => this.isSelected(student.id));
                },

                get filtered() {
                    const q = this.search.toLowerCase().trim();
                    if (!q) return this.students;
                    return this.students.filter(s =>
                        s.name.toLowerCase().includes(q) || s.email.toLowerCase().includes(q)
                    );
                },

                isSelected(id) {
                    return this.selectedIds.includes(id);
                },

                initials(name) {
                    return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
                },

                handleItemClick(student) {
                    if (student.enrolled) {
                        window.useToast().warning(`${student.name} is already enrolled in this class.`);
                        return;
                    }
                    this.toggleStudent(student);
                },

                toggleStudent(student) {
                    if (student.enrolled) return;
                    const idx = this.selectedIds.indexOf(student.id);
                    if (idx === -1) {
                        this.selectedIds.push(student.id);
                    } else {
                        this.selectedIds.splice(idx, 1);
                    }
                },

                toggleSelectAll() {
                    const selectableIds = this.selectableFiltered.map(student => student.id);

                    if (selectableIds.length === 0) return;

                    if (this.allFilteredSelected) {
                        this.selectedIds = this.selectedIds.filter(id => !selectableIds.includes(id));
                        return;
                    }

                    selectableIds.forEach(id => {
                        if (!this.selectedIds.includes(id)) {
                            this.selectedIds.push(id);
                        }
                    });
                },

                async submitStudents() {
                    if (this.selectedIds.length === 0 || this.submitting) return;
                    this.submitting = true;

                    try {
                        const response = await fetch(ADD_STUDENTS_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ student_ids: this.selectedIds }),
                        });

                        if (!response.ok) {
                            const data = await response.json();
                            const message = data.message ?? 'Failed to add students.';
                            window.useToast().error(message);
                            return;
                        }

                        const data = await response.json();
                        this.appendStudentsToTable(data.students);

                        this.selectedIds.forEach(id => {
                            const student = this.students.find(s => s.id === id);
                            if (student) student.enrolled = true;
                        });

                        const addedCount = data.students.length;
                        this.selectedIds = [];
                        this.search = '';

                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'add-student-modal' }));
                        window.useToast().success(`${addedCount} student(s) added successfully.`);

                    } catch {
                        window.useToast().error('An unexpected error occurred. Please try again.');
                    } finally {
                        this.submitting = false;
                    }
                },

                appendStudentsToTable(students) {
                    const tbody = document.getElementById('students-tbody');
                    const emptyRow = document.getElementById('students-empty-row');
                    if (emptyRow) emptyRow.remove();

                    students.forEach(student => {
                        const date = new Date(student.enrolled_at);
                        const formatted = date.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${escapeHtml(student.name)}</td>
                            <td>${escapeHtml(student.email)}</td>
                            <td>${formatted}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                },
            };
        }

        let activeTaskData = null;

        function openTaskDetails(btn) {
            activeTaskData = {
                id: btn.dataset.taskId,
                title: btn.dataset.taskTitle,
                instructions: btn.dataset.taskInstructions,
                due: btn.dataset.taskDue,
                dueTimeFmt: btn.dataset.taskDueTimeFormatted || '',
                files: JSON.parse(btn.dataset.taskFiles || '[]'),
            };

            document.getElementById('td-title').textContent = activeTaskData.title;
            document.getElementById('td-due-date').textContent = activeTaskData.due;
            document.getElementById('td-due-time').textContent = activeTaskData.dueTimeFmt;

            const instructionsEl = document.getElementById('td-instructions');
            if (activeTaskData.instructions && activeTaskData.instructions.trim() !== '') {
                instructionsEl.innerHTML = activeTaskData.instructions;
            } else {
                instructionsEl.innerHTML = '<p class="task-details-instructions-empty">No instructions provided.</p>';
            }

            const fileList = document.getElementById('td-file-list');
            const resCount = document.getElementById('td-resources-count');
            const resSection = document.getElementById('td-resources-section');
            const downloadAll = document.getElementById('td-download-all');

            fileList.innerHTML = '';

            if (activeTaskData.files.length === 0) {
                resSection.style.display = 'none';
            } else {
                resSection.style.display = 'flex';
                resCount.textContent = activeTaskData.files.length;

                if (activeTaskData.files.length > 1) {
                    downloadAll.style.display = 'block';
                } else {
                    downloadAll.style.display = 'none';
                }

                activeTaskData.files.forEach(f => {
                    const item = document.createElement('a');
                    item.href = `/storage/${f.path}`;
                    item.target = '_blank';
                    item.className = 'task-details-file-item';

                    const ext = f.name.split('.').pop().toLowerCase();
                    const sizeStr = formatBytes(f.size).toLowerCase();

                    item.innerHTML = `
                        <div class="task-details-file-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="task-details-file-meta">
                            <div class="task-details-file-name">${escapeHtml(f.name)}</div>
                            <div class="task-details-file-size">${sizeStr} • ${ext} document</div>
                        </div>
                    `;
                    fileList.appendChild(item);
                });
            }

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'task-details-modal' }));
        }

        function openEditMode(task) {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'task-details-modal' }));

            const form = document.getElementById('create-task-form');
            const hiddenId = document.getElementById('create-task-hidden-id');
            const titleInput = document.getElementById('task-title');
            const dateInput = document.getElementById('task-due-date');
            const timeInput = document.getElementById('task-due-time');
            const submitLabel = document.getElementById('create-task-submit-label');
            const modalTitle = document.getElementById('create-task-modal-title');

            form.action = `${UPDATE_TASK_BASE}/${task.id}`;
            hiddenId.value = task.id;

            titleInput.value = task.title;
            dateInput.value = task.dueDate;
            timeInput.value = task.dueTime;

            if (submitLabel) submitLabel.textContent = 'Save Changes';
            if (modalTitle) modalTitle.textContent = 'Edit Task';

            setTimeout(() => {
                if (window._quillInstance) {
                    window._quillInstance.root.innerHTML = task.instructions || '';
                    document.getElementById('task-instructions-hidden').value = task.instructions || '';
                }
            }, 80);

            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-task-modal' }));
        }

        function resetCreateTaskForm() {
            const form = document.getElementById('create-task-form');
            const hiddenId = document.getElementById('create-task-hidden-id');
            const titleInput = document.getElementById('task-title');
            const dateInput = document.getElementById('task-due-date');
            const timeInput = document.getElementById('task-due-time');
            const submitLabel = document.getElementById('create-task-submit-label');
            const modalTitle = document.getElementById('create-task-modal-title');

            form.action = STORE_TASK_URL;
            hiddenId.value = '';

            titleInput.value = '';
            dateInput.value = '';
            timeInput.value = '';

            if (submitLabel) submitLabel.textContent = 'Create Task';
            if (modalTitle) modalTitle.textContent = 'Create Task';

            if (window._quillInstance) {
                window._quillInstance.setContents([]);
                document.getElementById('task-instructions-hidden').value = '';
            }
        }

        function isPastDue(dateValue, timeValue) {
            if (!dateValue || !timeValue) return false;
            const due = new Date(`${dateValue}T${timeValue}`);
            if (Number.isNaN(due.getTime())) return false;
            return due.getTime() < Date.now();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('btn-add-student').addEventListener('click', () => {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'add-student-modal' }));
            });

            document.getElementById('btn-create-task').addEventListener('click', () => {
                resetCreateTaskForm();
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-task-modal' }));
            });

            document.getElementById('btn-cancel-create-task').addEventListener('click', () => {
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'create-task-modal' }));
            });

            document.querySelectorAll('.task-card-btn-view').forEach(btn => {
                btn.addEventListener('click', () => {
                    activeTaskData = {
                        id: btn.dataset.taskId,
                        title: btn.dataset.taskTitle,
                        instructions: btn.dataset.taskInstructions,
                        due: btn.dataset.taskDue,
                        dueTimeFmt: btn.dataset.taskDueTimeFormatted || '',
                        files: JSON.parse(btn.dataset.taskFiles || '[]'),
                    };
                    openTaskDetails(btn);
                });
            });

            document.querySelectorAll('.task-card-btn-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    const task = {
                        id: btn.dataset.taskId,
                        title: btn.dataset.taskTitle,
                        instructions: btn.dataset.taskInstructions,
                        due: btn.dataset.taskDue,
                        dueDate: btn.dataset.taskDueDate,
                        dueTime: btn.dataset.taskDueTime,
                        status: btn.dataset.taskStatus,
                        files: JSON.parse(btn.dataset.taskFiles || '[]'),
                    };
                    openEditMode(task);
                });
            });

            document.querySelectorAll('.task-card-btn-delete').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const taskId = btn.dataset.taskId;
                    if (!taskId) return;

                    const { isConfirmed } = await window.useConfirm().confirm({
                        title: 'Delete this task?',
                        text: 'This will permanently remove the task and all associated student submissions. This action cannot be undone.',
                        icon: 'warning',
                        confirmButtonText: 'Yes, delete it',
                        confirmButtonColor: 'var(--color-error)',
                    });

                    if (isConfirmed) {
                        const form = document.getElementById('delete-task-form');
                        form.action = `${UPDATE_TASK_BASE}/${taskId}`;
                        form.submit();
                    }
                });
            });

            document.getElementById('btn-edit-class').addEventListener('click', async () => {
                const { isConfirmed } = await window.useConfirm().confirm({
                    title: 'Edit Class?',
                    text: 'You are about to edit the details of this class.',
                    icon: 'info',
                    confirmButtonText: 'Yes, edit it',
                    confirmButtonColor: 'var(--color-primary)',
                });

                if (isConfirmed) {
                    window.useToast().info('Edit class — coming soon.');
                }
            });

            document.getElementById('btn-archive-class').addEventListener('click', async () => {
                const { isConfirmed } = await window.useConfirm().confirm({
                    title: 'Archive this class?',
                    text: 'Students will lose access to this class. This action can be undone later.',
                    icon: 'warning',
                    confirmButtonText: 'Yes, archive it',
                    confirmButtonColor: 'var(--color-error)',
                });

                if (isConfirmed) {
                    document.getElementById('archive-class-form').submit();
                }
            });

            // ========== TASK REFERENCE FILES LOGIC ==========
            let dropZone = null;
            let fileInput = null;
            let refFileList = null;
            let selectedFiles = [];
            let fileUploadBound = false;

            function setupTaskFileUpload() {
                dropZone = document.getElementById('task-drop-zone');
                fileInput = document.getElementById('task-file-input');
                refFileList = document.getElementById('task-ref-file-list');

                if (!dropZone || !fileInput || !refFileList) return false;
                if (fileUploadBound) return true;

                fileUploadBound = true;

                // Label with 'for' attribute handles clicking automatically now
                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('file-drop-zone--drag-over');
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.classList.remove('file-drop-zone--drag-over');
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('file-drop-zone--drag-over');
                    handleFiles(e.dataTransfer.files);
                });

                fileInput.addEventListener('change', (e) => {
                    handleFiles(e.target.files);
                });

                return true;
            }

            function ensureTaskFileUploadReady(attempt = 0) {
                if (setupTaskFileUpload()) return;
                if (attempt >= 8) return;
                setTimeout(() => ensureTaskFileUploadReady(attempt + 1), 50);
            }

            function handleFiles(files) {
                const newFiles = Array.from(files);

                // Filter out duplicates by name
                newFiles.forEach(file => {
                    if (!selectedFiles.some(f => f.name === file.name)) {
                        if (selectedFiles.length < 5) {
                            selectedFiles.push(file);
                        } else {
                            window.useToast().warning('Maximum 5 reference files allowed.');
                        }
                    }
                });

                updateFileList();
                syncInput();
            }

            function updateFileList() {
                if (!refFileList) return;
                refFileList.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = 'ref-file-item';
                    item.innerHTML = `
                        <div class="ref-file-item-info">
                            <span class="ref-file-item-name">${escapeHtml(file.name)}</span>
                            <span class="ref-file-item-size">${formatBytes(file.size)}</span>
                        </div>
                        <button type="button" class="ref-file-remove" data-index="${index}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    `;
                    refFileList.appendChild(item);
                });

                document.querySelectorAll('.ref-file-remove').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = parseInt(btn.dataset.index);
                        selectedFiles.splice(index, 1);
                        updateFileList();
                        syncInput();
                    });
                });
            }

            function syncInput() {
                if (!fileInput) return;
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;

                // Also sync to edit form's file input if it exists
                const editFileInput = document.getElementById('edit-task-file-input');
                if (editFileInput) {
                    const editDt = new DataTransfer();
                    selectedFiles.forEach(file => editDt.items.add(file));
                    editFileInput.files = editDt.files;
                }
            }

            // Reset files on modal open (create mode)
            window.addEventListener('open-modal', (e) => {
                if (e.detail !== 'create-task-modal') return;

                ensureTaskFileUploadReady();

                if (!document.getElementById('create-task-hidden-id').value) {
                    selectedFiles = [];
                    updateFileList();
                    syncInput();
                }
            });

            ensureTaskFileUploadReady();

            @if(session('success'))
                window.useToast().success('{{ session('success') }}');
            @endif

            @if(session('error'))
                window.useToast().error('{{ session('error') }}');
            @endif

            @if($errors->any())
                window.useToast().error('{!! addslashes($errors->first()) !!}');
            @endif

            // Ensure files are synced before submission
            const createForm = document.getElementById('create-task-form');
            if (createForm) {
                createForm.addEventListener('submit', (e) => {
                    const dateInput = document.getElementById('task-due-date');
                    const timeInput = document.getElementById('task-due-time');
                    const dueDate = dateInput ? dateInput.value : '';
                    const dueTime = timeInput ? timeInput.value : '';

                    if (isPastDue(dueDate, dueTime)) {
                        e.preventDefault();
                        window.useToast().error('Submission time has already passed. Please choose a future due time.');
                        return;
                    }

                    syncInput();
                });
            }

            const editForm = document.getElementById('edit-task-form');
            if (editForm) {
                editForm.addEventListener('submit', () => syncInput());
            }
        });
    </script>


</x-app-layout>