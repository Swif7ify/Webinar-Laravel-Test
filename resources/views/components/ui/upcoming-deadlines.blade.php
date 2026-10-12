@props(['deadlines' => []])

<div class="upcoming-deadlines">

    {{-- ========== HEADER ========== --}}
    <div class="upcoming-deadlines__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="upcoming-deadlines__header-icon">
            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd" />
        </svg>
        <h3 class="upcoming-deadlines__title">Upcoming Deadlines</h3>
    </div>

    {{-- ========== TIMELINE ========== --}}
    <ol class="deadline-timeline">
        @forelse ($deadlines as $index => $deadline)
            <li class="deadline-item deadline-item--{{ $deadline['urgency'] }}">
                <div class="deadline-item__connector">
                    <div class="deadline-item__dot"></div>
                    @if (!$loop->last)
                        <div class="deadline-item__line"></div>
                    @endif
                </div>
                <div class="deadline-item__card">
                    <div class="deadline-item__meta">
                        <span class="deadline-item__date">{{ $deadline['date'] }}</span>
                        <span class="deadline-item__time">{{ $deadline['time'] }}</span>
                    </div>
                    <p class="deadline-item__course">{{ $deadline['courseCode'] }}</p>
                    <p class="deadline-item__title">{{ $deadline['title'] }}</p>
                </div>
            </li>
        @empty
            <li class="deadline-timeline__empty">
                <p>No upcoming deadlines. You're all caught up! ✅</p>
            </li>
        @endforelse
    </ol>

</div>
