<section>
    <div class="profile-section-intro">
        <p class="profile-section-description">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div class="profile-form__field">
            <x-forms.input 
                id="name" 
                name="name" 
                type="text" 
                :label="__('Name')"
                :value="old('name', $user->name)" 
                required 
                autofocus 
                autocomplete="name" 
                icon="user"
                :error="$errors->first('name')"
            />
        </div>

        <div class="profile-form__field">
            <x-forms.input 
                id="email" 
                name="email" 
                type="email" 
                :label="__('Email')"
                :value="old('email', $user->email)" 
                required 
                autocomplete="username" 
                icon="email"
                :error="$errors->first('email')"
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="profile-form__verify-section">
                    <p class="profile-form__verify-text">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="profile-form__verify-button">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="profile-form__verify-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="profile-form__actions">
            <x-ui.button variant="primary" type="submit">{{ __('Save') }}</x-ui.button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="profile-form__success-message"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
