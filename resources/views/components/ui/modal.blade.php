@props(['id', 'title' => null, 'panelClass' => '', 'titleId' => null])

<template x-teleport="body">
<div x-data="{ show: false }" 
     x-on:open-modal.window="if ($event.detail === '{{ $id }}') { show = true; document.body.style.overflow = 'hidden'; }"
     x-on:close-modal.window="if ($event.detail === '{{ $id }}') { show = false; document.body.style.overflow = ''; }"
     x-show="show"
     class="app-modal-backdrop"
     style="display: none;"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="app-modal-panel {{ $panelClass }}" 
         x-show="show" 
         x-on:click.outside="show = false; document.body.style.overflow = '';"
         x-on:keydown.escape.window="show = false; document.body.style.overflow = '';"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         x-on:click.stop>
        
        @if($title)
            <div class="app-modal-header">
                <h3 class="app-modal-title" @if($titleId) id="{{ $titleId }}" @endif>{{ $title }}</h3>
                <button type="button" x-on:click="show = false; document.body.style.overflow = '';" class="app-modal-close">&times;</button>
            </div>
        @endif

        <div class="app-modal-body">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="app-modal-footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
</template>
