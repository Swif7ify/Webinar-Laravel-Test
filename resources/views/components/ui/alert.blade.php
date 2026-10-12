@props([
    'type'    => 'info',
    'message',
])

<div class="alert alert--{{ $type }}" role="alert" id="alert-{{ $type }}">
    {{ $message }}
</div>
