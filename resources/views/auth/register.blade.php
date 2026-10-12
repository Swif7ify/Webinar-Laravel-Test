<x-guest-layout>
    <div class="login-page">

        {{-- ========== HERO ========== --}}
        <div class="login-hero">
            <h1 class="login-hero__title" style="margin-top: -2rem;">
                Start managing
                <span class="login-hero__title-accent">Assignments</span>
            </h1>

            <p class="login-hero__subtitle" style="max-width: 400px; margin-bottom: 0;">
                Create your account to join classes, submit work, or start managing your students seamlessly.
            </p>

            <div class="features-card">
                <div class="features-card__header">
                    <div class="features-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="features-card__info">
                        <span class="features-card__title">Seamless Collaboration</span>
                        <span class="features-card__subtitle">Connect instantly</span>
                    </div>
                    <span class="features-card__badge">Secure</span>
                </div>
                
                <div class="features-card__list">
                    <div class="features-card__item">
                        <div class="features-card__item-icon features-card__item-icon--success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span>Role-based access control</span>
                    </div>
                    <div class="features-card__item">
                        <div class="features-card__item-icon features-card__item-icon--primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        </div>
                        <span>Streamlined submissions</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== REGISTER PANEL ========== --}}
        <div class="login-panel register-panel">
            <div class="login-card">

                {{-- ========== HEADER ========== --}}
                <div class="login-card__header">
                    <h2>Create Your Account</h2>
                    <p>Enter your details to get started</p>
                </div>

                @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
                    <x-ui.alert type="error" message="Please fix the errors below to continue." />
                @endif

                <form method="POST" action="{{ route('register') }}" class="login-form">
                    @csrf

                    {{-- Name --}}
                    <x-forms.input 
                        name="name" 
                        label="Full Name" 
                        type="text" 
                        icon="user"
                        placeholder="John Doe" 
                        :required="true" 
                        :error="$errors->first('name')"
                        value="{{ old('name') }}"
                        autocomplete="name" />

                    {{-- Email --}}
                    <x-forms.input 
                        name="email" 
                        label="Email Address" 
                        type="email" 
                        icon="email"
                        placeholder="you@example.com" 
                        :required="true" 
                        :error="$errors->first('email')"
                        value="{{ old('email') }}"
                        autocomplete="username" />

                    {{-- Passwords --}}
                    <div class="form-row-2">
                        <x-forms.input 
                            name="password" 
                            label="Password" 
                            type="password" 
                            icon="lock" 
                            placeholder="••••••••"
                            :required="true" 
                            :error="$errors->first('password')" 
                            autocomplete="new-password" />

                        <x-forms.input 
                            name="password_confirmation" 
                            label="Confirm" 
                            type="password" 
                            icon="lock" 
                            placeholder="••••••••"
                            :required="true" 
                            autocomplete="new-password" />
                    </div>

                    {{-- Terms Checkbox --}}
                    <div class="terms-checkbox-wrapper">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms" style="margin: 0; color: inherit;">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-signin" id="btn-register">
                        Create Account
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </form>

            </div>

            <p class="login-page__footer">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </p>
        </div>
    </div>
</x-guest-layout>
