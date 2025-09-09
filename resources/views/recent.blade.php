<x-app-layout>
    <x-slot name="title">Recent Items</x-slot>

    @include('partials.breadcrumbs')

    @include('partials.recent-list', ['items' => $recentItems])
</x-app-layout>