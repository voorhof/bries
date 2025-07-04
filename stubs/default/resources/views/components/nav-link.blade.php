@props([
    'active',
    'disabled',
])

@php
    $classes = ($active ?? false)
                ? 'nav-link active'
                : 'nav-link';

    $classes = ($disabled ?? false)
                ? $classes . ' disabled'
                : $classes;

    $ariaCurrent = ($active ?? false)
                ? 'page'
                : 'false';

    $ariaDisabled = ($disabled ?? false)
                ? 'true'
                : 'false';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'aria-current' => $ariaCurrent, 'aria-disabled' => $ariaDisabled]) }}>
    {{ $slot }}
</a>
