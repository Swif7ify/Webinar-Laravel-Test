<section>
    <div class="profile-section-intro">
        <p class="profile-section-description">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div class="profile-form__field">
            <x-forms.input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                :label="__('Current Password')"
                autocomplete="current-password" 
                icon="lock"
                :error="$errors->updatePassword->first('current_password')"
            />
        </div>

        <div class="profile-form__field">
            <x-forms.input 
                id="update_password_password" 
                name="password" 
                type="password" 
                :label="__('New Password')"
                autocomplete="new-password" 
                icon="lock"
                :error="$errors->updatePassword->first('password')"
            />
        </div>

        <div class="profile-form__field">
            <x-forms.input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                :label="__('Confirm Password')"
                autocomplete="new-password" 
                icon="lock"
                :error="$errors->updatePassword->first('password_confirmation')"
            />
        </div>

        <div class="profile-form__actions">
            <x-ui.button variant="primary" type="submit">{{ __('Save') }}</x-ui.button>

            @if (session('status') === 'password-updated')
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
