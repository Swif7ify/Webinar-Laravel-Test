<section>
    <div class="profile-section-intro">
        <p class="profile-section-description">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <div class="profile-form__delete-section">
        <x-ui.button
            variant="danger"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Delete Account') }}</x-ui.button>

        <x-ui.modal id="confirm-user-deletion">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="app-modal-body">
                    <h3 class="profile-section-header__title">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h3>

                    <p class="profile-section-header__description">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="profile-form__field">
                        <x-forms.input
                            id="password"
                            name="password"
                            type="password"
                            :label="__('Password')"
                            placeholder="{{ __('Password') }}"
                            :error="$errors->userDeletion->first('password')"
                        />
                    </div>
                </div>

                <div class="app-modal-footer">
                    <x-ui.button variant="danger" x-on:click.prevent="$dispatch('close-modal', 'confirm-user-deletion')">
                        {{ __('Cancel') }}
                    </x-ui.button>

                    <x-ui.button variant="danger" type="submit">
                        {{ __('Delete Account') }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.modal>
    </div>
</section>
