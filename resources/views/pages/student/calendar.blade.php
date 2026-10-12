<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="My Calendar" />
    </x-slot>

    <div class="calendar-page-container">
        <x-ui.calendar :events="$events" />
    </div>
</x-app-layout>
