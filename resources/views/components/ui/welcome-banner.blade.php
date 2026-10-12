@props(['name', 'dueCount' => 0])

<div class="welcome-banner">
    <div class="welcome-banner__content">
        <p class="welcome-banner__label">Welcome back,</p>
        <h2 class="welcome-banner__name">{{ $name }}</h2>
        <p class="welcome-banner__subtitle">
            Stay on top of your coursework. You have <strong>{{ $dueCount }} {{ Str::plural('assignment', $dueCount) }}</strong> due this week.<br>
            Let's make it a productive day!
        </p>
        <a href="{{ route('student.calendar') }}" class="welcome-banner__cta">View Calendar</a>
    </div>
</div>
