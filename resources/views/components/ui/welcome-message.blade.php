@props(['name', 'subtitle'])

<div class="welcome-message">
    <h2 class="welcome-heading">Welcome back, {{ $name }}! 👋</h2>
    <p class="text-muted welcome-subtitle">{{ $subtitle }}</p>
</div>
