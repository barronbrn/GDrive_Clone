<x-app-layout>
    <x-slot name="title">Recent</x-slot>

    @include('partials.breadcrumbs')

    @include('partials.recent-list', ['items' => $items, 'folder' => $folder ?? null])
</x-app-layout>