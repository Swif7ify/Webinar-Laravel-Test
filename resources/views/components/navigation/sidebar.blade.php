@props(['role' => 'teacher'])

<aside class="app-sidebar hidden-mobile">
    <div class="sidebar-header">
        <div class="sidebar-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002c-.874.494-1.949.494-2.823 0l-.003-.002a49.948 49.948 0 00-9.902-3.912.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
                <path
                    d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.251 4.284a.75.75 0 01-.46.711 47.87 47.87 0 00-8.105 4.342.75.75 0 01-.832 0 47.87 47.87 0 00-8.104-4.342.75.75 0 01-.461-.71c.03-1.441.117-2.87.251-4.284a48.45 48.45 0 017.665 3.282c.49.278 1.077.278 1.567 0z" />
            </svg>
        </div>
        <span class="sidebar-logo-text">Portal</span>
    </div>

    <div class="sidebar-section-title">Menu</div>

    <nav class="sidebar-nav">
        @if ($role === 'student')

            <a href="{{ route('student.dashboard') }}"
                class="sidebar-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"
                        clip-rule="evenodd" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('student.assignments.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('student.assignments.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zM12.75 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12zM9 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12z"
                        clip-rule="evenodd" />
                    <path
                        d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                </svg>
                My Assignments
            </a>

            <a href="{{ route('student.classes.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('student.classes.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                </svg>
                My Classes
            </a>

            <a href="{{ route('student.calendar') }}"
                class="sidebar-nav-item {{ request()->routeIs('student.calendar') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z"
                        clip-rule="evenodd" />
                </svg>
                Calendar
            </a>
        @else

            <a href="{{ route('teacher.dashboard') }}"
                class="sidebar-nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"
                        clip-rule="evenodd" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('teacher.classes.index') }}"
                class="sidebar-nav-item {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zM12.75 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12zM9 12a.75.75 0 00-1.5 0v2.25a.75.75 0 001.5 0V12z"
                        clip-rule="evenodd" />
                    <path
                        d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                </svg>
                Classes
            </a>

            <a href="{{ route('teacher.submissions.history') }}"
                class="sidebar-nav-item {{ request()->routeIs('teacher.submissions.history') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z"
                        clip-rule="evenodd" />
                </svg>
                Submission History
            </a>

            <a href="{{ route('teacher.calendar') }}"
                class="sidebar-nav-item {{ request()->routeIs('teacher.calendar') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z"
                        clip-rule="evenodd" />
                </svg>
                Calendar
            </a>


        @endif
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}"
            class="sidebar-nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.816l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.097.078.16.208.151.314a7.494 7.494 0 000 1.396c.009.106-.054.236-.151.314l-.84.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.816l1.019-.382c.116-.043.284-.032.45.083.314.214.64.405.987.57.181.088.276.228.297.348l.178 1.072c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.348.348-.165.673-.356.986-.57.167-.115.335-.126.45-.083l1.02.382a1.875 1.875 0 002.28-.816l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.097-.078-.16-.208-.151-.314a7.493 7.493 0 000-1.396c-.009-.106.054-.236.151-.314l.84-.692a1.875 1.875 0 00.432-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.816l-1.02.382c-.114.043-.282.032-.449-.083a7.49 7.49 0 00-.986-.57c-.183-.088-.277-.228-.298-.348l-.177-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"
                    clip-rule="evenodd" />
            </svg>
            Profile & Settings
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full m-0 p-0">
            @csrf
            <button type="button" class="sidebar-logout-btn"
                onclick="let form = this.closest('form'); window.useConfirm().confirm({title: 'Sign Out', text: 'Are you sure you want to log out?', confirmButtonText: 'Yes, log out'}).then((res) => { if (res.isConfirmed) form.submit(); })">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z"
                        clip-rule="evenodd" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>