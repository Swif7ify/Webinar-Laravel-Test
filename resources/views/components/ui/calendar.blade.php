@props(['events' => []])

<div class="calendar-card animate-fade-in">
    <div id="calendar"></div>
</div>

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const rawEvents = @json($events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev today',
                    center: 'title',
                    right: 'next'
                },
                showNonCurrentDates: false,
                height: 'auto',
                aspectRatio: 1.8,
                events: rawEvents,
                eventDisplay: 'block',
                
                dayMaxEvents: 3, 
                moreLinkClick: 'popover',
                moreLinkText: function(num) {
                    return `+${num} more`;
                },
                
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
                
                themeSystem: 'standard'
            });

            calendar.render();
        });
    </script>
@endpush
