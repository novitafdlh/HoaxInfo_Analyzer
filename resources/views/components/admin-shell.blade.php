@props([
    'title' => 'Panel Admin',
    'containerClass' => 'max-w-7xl',
    'contentClass' => 'space-y-6',
])

<x-portal-shell :title="$title" mode="admin" :container-class="$containerClass" :content-class="$contentClass">
    @isset($pageHeader)
        <x-slot name="pageHeader">
            {{ $pageHeader }}
        </x-slot>
    @endisset

    {{ $slot }}
</x-portal-shell>
