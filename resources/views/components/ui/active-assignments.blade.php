@props(['assignments' => [], 'viewAllHref' => '#'])

<div class="active-assignments">

    <div class="active-assignments__header">
        <h3 class="active-assignments__title">Active Assignments</h3>
        <a href="{{ $viewAllHref }}" class="active-assignments__view-all">View All</a>
    </div>

    <ul class="active-assignments__list">
        @forelse ($assignments as $assignment)
            <li class="assignment-row">

                <div class="assignment-row__icon assignment-row__icon--{{ $assignment['iconColor'] }}">
                    {!! $assignment['icon'] !!}
                </div>

                <div class="assignment-row__info">
                    <p class="assignment-row__title">{{ $assignment['title'] }}</p>
                    <p class="assignment-row__meta">
                        {{ $assignment['courseCode'] }} &bull; <span class="assignment-row__professor">{{ $assignment['professor'] }}</span>
                    </p>
                    <div class="assignment-row__badges">
                        <span class="badge badge--{{ $assignment['statusVariant'] }}">
                            {{ $assignment['status'] }}
                        </span>
                        <span class="assignment-row__due assignment-row__due--{{ $assignment['dueVariant'] }}">
                            {!! $assignment['dueIcon'] !!}
                            {{ $assignment['dueText'] }}
                        </span>
                    </div>
                </div>

                <div class="assignment-row__actions">
                    @if ($assignment['showViewDetails'])
                        <a href="{{ $assignment['detailsHref'] ?? '#' }}" class="btn btn-secondary btn-sm">View Details</a>
                    @endif
                </div>

            </li>
        @empty
            <li class="active-assignments__empty">
                <p>No active assignments right now. Enjoy the break! 🎉</p>
            </li>
        @endforelse
    </ul>

</div>
