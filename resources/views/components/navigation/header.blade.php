@props(['title' => 'Dashboard'])

<header class="app-header">
    <div class="header-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="header-actions">


        <div class="header-profile-dropdown">
            <div class="header-profile-trigger" style="cursor: default; pointer-events: none;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->name }}" class="header-avatar" />
                <div class="header-profile-info">
                    <span class="header-profile-name" style="margin: auto 0;">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
