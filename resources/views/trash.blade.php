<x-app-layout>
    <x-slot name="title">Trash</x-slot>

    @include('partials.trash-list', ['items' => $items])
</x-app-layout>