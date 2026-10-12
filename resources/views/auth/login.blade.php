<x-guest-layout>
    <div class="login-page">

        {{-- ========== HERO ========== --}}
        <div class="login-hero">

            <h1 class="login-hero__title">
                Welcome back to
                <span class="login-hero__title-accent">Your Classroom</span>
            </h1>

            <p class="login-hero__subtitle">
                Sign in to access your dashboard, manage assignments, and track progress all in one place.
            </p>

            <div class="overview-card">
                <div class="overview-card__header">
                    <div class="overview-card__avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                            <path d="M6 12v5c3 3 9 3 12 0v-5" />
                        </svg>
                    </div>
                    <div class="overview-card__info">
                        <span class="overview-card__title">Weekly Overview</span>
                        <span class="overview-card__subtitle">Quick stats update</span>
                    </div>
                    <span class="overview-card__badge">Active</span>
                </div>
                <div class="overview-card__progress-row">
                    <span class="overview-card__progress-label">Tasks Completed</span>
                    <span class="overview-card__progress-value">75%</span>
                </div>
                <div class="overview-card__progress-track">
                    <div class="overview-card__progress-fill"></div>
                </div>
            </div>
        </div>

        {{-- ========== LOGIN PANEL ========== --}}
        <div class="login-panel">
            <div class="login-card">

                {{-- ========== HEADER ========== --}}
                <div class="login-card__header">
                    <h2>Sign In to Your Account</h2>
                    <p>Please enter your credentials below</p>
                </div>

                {{-- ========== SESSION STATUS ========== --}}
                @if(session('status'))
                    <x-ui.alert type="success" :message="session('status')" />
                @endif

                {{-- ========== FORM ========== --}}
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <x-forms.input name="email" label="Email or Username" type="email" icon="email"
                        placeholder="Enter your email or username" :required="true" :error="$errors->first('email')"
                        autocomplete="username" />

                    <x-forms.input name="password" label="Password" type="password" icon="lock" placeholder="••••••••"
                        :required="true" :error="$errors->first('password')" autocomplete="current-password" />

                    <div class="login-remember">
                        <label class="remember-label" for="remember-me">
                            <input type="checkbox" name="remember" id="remember-me">
                            Remember me
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-signin" id="btn-login">
                        Sign In
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </form>

            </div>

            <p class="login-page__footer">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </p>
        </div>
    </div>
</x-guest-layout>