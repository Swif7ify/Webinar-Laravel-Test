<x-app-layout>
    <x-slot name="header">
        <x-navigation.header title="Dashboard Overview" />
    </x-slot>

    <div class="dashboard-container">

        <x-ui.welcome-banner
            name="{{ Auth::user()->name }}"
            :due-count="$dueCount ?? 0"
        />

        <div class="dashboard-bottom-grid">
            <x-ui.active-assignments
                :assignments="$assignments ?? []"
                :view-all-href="route('student.assignments.index')"
            />
            <x-ui.upcoming-deadlines :deadlines="$deadlines ?? []" />
        </div>

    </div>
</x-app-layout>