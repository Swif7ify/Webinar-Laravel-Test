<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Assignment Details" />
    </x-slot>

    <div class="assignment-details" x-data="window.useCountdown('{{ $task->due_date->toIso8601String() }}')">

        <div class="assignment-details__header-actions">
            <a href="{{ $backUrl }}" class="assignment-details__back-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="assignment-details__header-title">
                <h2>Assignment Details</h2>
                <p>{{ $task->schoolClass->code ?? '' }} &bull; {{ $task->schoolClass->subject->name ?? '' }}</p>
            </div>
        </div>

        <div class="assignment-details__grid">

            <div class="assignment-details__column-main">

                <div class="assignment-hero-card">
                    <div class="assignment-details__meta">
                        <x-ui.badge :variant="$statusBadgeVariant" :label="$statusBadgeLabel" />
                        <span class="assignment-details__meta-text">{{ $task->schoolClass->code ?? '' }} &bull; {{ $task->schoolClass->subject->name ?? '' }}</span>
                    </div>
                    <h1 class="assignment-details__title">{{ $task->title }}</h1>
                    <div class="assignment-details__subtitle">
                        <div class="assignment-details__teacher">
                            <div class="assignment-details__teacher-avatar">{{ $task->teacher ? strtoupper(substr($task->teacher->name, 0, 1)) : 'T' }}</div>
                            <span>{{ $task->teacher->name ?? 'Instructor' }}</span>
                        </div>
                        <span>&bull;</span>
                        <div class="assignment-details__posted">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Posted: {{ $task->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <div class="assignment-card-box">
                    <div class="assignment-card-box__header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"></path></svg>
                        <h3 class="assignment-card-box__title">Assignment Instructions</h3>
                    </div>
                    <div class="assignment-card-box__body prose">
                        {!! $instructionsText !!}
                    </div>
                </div>

                @if($submission && $submission->status === 'checked')
                    <div class="assignment-card-box assignment-card-box--checked">
                        <div class="assignment-card-box__header">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="assignment-card-box__title">Review Status</h3>
                        </div>
                        <div class="grade-feedback-content">
                            <p class="checked-status-text">Your submission has been reviewed and marked as checked by the instructor.</p>
                        </div>
                    </div>
                @endif
                @if($task->referenceFiles && $task->referenceFiles->count() > 0)
                    <div class="assignment-card-box">
                        <div class="assignment-card-box__header">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <h3 class="assignment-card-box__title">Teacher Attachments</h3>
                        </div>
                        <div class="assignment-attachments">
                            @foreach($task->referenceFiles as $file)
                                <div class="attachment-item">
                                    <div class="attachment-item__info">
                                        <div class="attachment-item__icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <div class="attachment-item__text">
                                            <p class="attachment-item__name">{{ $file->original_name }}</p>
                                            <p class="attachment-item__type">{{ number_format(($file->size_bytes ?? 0) / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('files.reference.download', $file->id) }}" class="attachment-item__download">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <div class="assignment-details__column-side">

                <div class="assignment-due-card">
                    <div class="assignment-due-card__content">
                        <p class="assignment-due-card__label">Due Date</p>
                        <p class="assignment-due-card__date">{{ $task->due_date->format('M d, Y \a\t g:i A') }}</p>

                        <div class="countdown-grid" x-show="countdown.days !== '--'">
                            <div class="countdown-item">
                                <div class="countdown-item__val" x-text="countdown.days"></div>
                                <div class="countdown-item__label">Days</div>
                            </div>
                            <div class="countdown-item">
                                <div class="countdown-item__val" x-text="countdown.hours"></div>
                                <div class="countdown-item__label">Hrs</div>
                            </div>
                            <div class="countdown-item">
                                <div class="countdown-item__val" x-text="countdown.minutes"></div>
                                <div class="countdown-item__label">Min</div>
                            </div>
                            <div class="countdown-item">
                                <div class="countdown-item__val" x-text="countdown.seconds"></div>
                                <div class="countdown-item__label">Sec</div>
                            </div>
                        </div>

                        <div class="countdown-expired" x-show="countdown.days === '--'">
                            <span>Deadline Passed</span>
                        </div>
                    </div>
                </div>

                <div class="submission-card">
                    <h3 class="submission-card__title">Your Submission</h3>

                    <div class="submission-card__status">
                        <span class="submission-card__status-label">Status:</span>
                        @if($submission)
                            <x-ui.badge variant="success" label="Submitted" />
                        @else
                            <x-ui.badge variant="neutral" label="Not Submitted" />
                        @endif
                    </div>

                    @php
                        $canSubmit = !$task->due_date->isPast();
                        $canRemoveSubmission = $submission && $submission->status !== 'checked';
                    @endphp

                    @if($submission && $submission->files->count() > 0)
                        <div class="submission-card__file">
                            <span class="submission-card__file-label">Current Submission</span>
                            <div class="submission-files-list" x-data="{ expanded: false }">
                                @foreach($submission->files as $index => $file)
                                    <div class="attachment-item" x-show="expanded || {{ $index < 3 ? 'true' : 'false' }}" x-transition>
                                        <div class="attachment-item__info">
                                            <div class="attachment-item__icon">
                                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <div class="attachment-item__text">
                                                <p class="attachment-item__name">{{ $file->original_name }}</p>
                                                <p class="attachment-item__type">{{ number_format(($file->size_bytes ?? 0) / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        @if($canRemoveSubmission)
                                            <form action="{{ route('student.assignments.submission.delete', $task->id) }}" method="POST" onsubmit="event.preventDefault(); window.useConfirm().confirm({ title: 'Remove submission', text: 'Remove your submitted files? You can upload a new one after this.', confirmButtonText: 'Yes, remove' }).then((res) => { if (res.isConfirmed) event.target.submit(); });">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="attachment-item__download" aria-label="Remove submission">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('files.download', $file->id) }}" class="attachment-item__download" aria-label="Download file">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach

                                @if($submission->files->count() > 3)
                                    <button type="button" class="submission-files-list__toggle" @click="expanded = !expanded">
                                        <span x-text="expanded ? 'See less' : 'See {{ $submission->files->count() - 3 }} more...'"></span>
                                        <svg :class="{'is-rotated': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="submission-card__steps">
                        <div class="submission-step {{ !$submission ? 'is-active' : '' }}">
                            <span class="submission-step__index">1</span>
                            <span class="submission-step__text">Upload your files</span>
                        </div>
                        <div class="submission-step {{ $submission ? 'is-active' : '' }}">
                            <span class="submission-step__index">2</span>
                            <span class="submission-step__text">Submit assignment</span>
                        </div>
                    </div>

                    <div class="submission-card__info">
                        <div class="submission-info-row">
                            <span class="submission-info-row__label">Allowed types:</span>
                            <span class="submission-info-row__value">{{ $acceptedFormatsText }}</span>
                        </div>
                        <div class="submission-info-row">
                            <span class="submission-info-row__label">Max file size:</span>
                            <span class="submission-info-row__value">{{ $task->max_file_size_mb ?? 10 }} MB</span>
                        </div>
                    </div>

                    <form action="{{ route('student.assignments.submit', $task->id) }}" method="POST" enctype="multipart/form-data" id="submission-form">
                        @csrf
                        <label class="submission-upload-zone {{ !$canSubmit ? 'is-disabled' : '' }}" for="submission-file-input" x-data="{ fileCount: 0 }">
                            <input type="file" name="files[]" id="submission-file-input" class="submission-upload-zone__input" 
                                @change="
                                    fileCount = $event.target.files.length;
                                    let totalSize = 0;
                                    for(let f of $event.target.files) totalSize += f.size;
                                    if(totalSize > ({{ $task->max_file_size_mb ?? 10 }} * 1024 * 1024)) {
                                        window.useToast().error('Total file size exceeds the limit of {{ $task->max_file_size_mb ?? 10 }}MB');
                                        $event.target.value = '';
                                        fileCount = 0;
                                    }
                                " 
                                multiple required :disabled="{{ !$canSubmit ? 'true' : 'false' }}" />
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="submission-upload-zone__title" x-text="fileCount > 0 ? fileCount + (fileCount === 1 ? ' file' : ' files') + ' selected' : '{{ $canSubmit ? 'Click to upload files' : 'Submission closed' }}'"></p>
                            <p class="submission-upload-zone__subtitle" x-show="fileCount === 0 && {{ $canSubmit ? 'true' : 'false' }}">or drag and drop here</p>
                        </label>

                        @error('files')
                            <p class="text-error text-sm mt-2">{{ $message }}</p>
                        @enderror
                        @error('files.*')
                            <p class="text-error text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <x-ui.button type="submit" variant="primary" class="submission-card__submit-btn w-full mt-4" :disabled="!$canSubmit">
                            {{ $submission ? 'Re-submit Assignment' : 'Submit Assignment' }}
                        </x-ui.button>
                    </form>

                    <p class="submission-card__note">Supported files are validated when upload is enabled.</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
