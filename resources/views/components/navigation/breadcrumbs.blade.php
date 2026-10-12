@props(['links' => []])

<nav class="breadcrumbs" aria-label="Breadcrumb">
    <ol class="breadcrumbs-list">
        @foreach($links as $index => $link)
            <li class="breadcrumbs-item">
                @if(isset($link['url']) && !$loop->last)
                    <a href="{{ $link['url'] }}" class="breadcrumbs-link">{{ $link['label'] }}</a>
                @else
                    <span class="breadcrumbs-current" aria-current="page">{{ $link['label'] }}</span>
                @endif
                
                @if(!$loop->last)
                    <span class="breadcrumbs-separator">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
