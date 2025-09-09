<x-app-layout>
    <x-slot name="title">Recent Items</x-slot>

    @include('partials.recent-list', ['items' => $items])
</x-app-layout>