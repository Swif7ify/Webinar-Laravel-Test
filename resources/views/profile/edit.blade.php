<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Profile Settings" />
    </x-slot>

    <div class="profile-page-container">
        <div class="profile-page-header">
            <div>
                <h2 class="profile-page-title">Account Settings</h2>
                <p class="profile-page-subtitle">Manage your profile information, password, and account security</p>
            </div>
        </div>

        <div class="profile-sections-stack">
            <x-ui.card title="{{ __('Profile Information') }}">
                @include('profile.partials.update-profile-information-form')
            </x-ui.card>

            <x-ui.card title="{{ __('Update Password') }}">
                @include('profile.partials.update-password-form')
            </x-ui.card>

            <x-ui.card title="{{ __('Delete Account') }}">
                @include('profile.partials.delete-user-form')
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
