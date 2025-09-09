<x-app-layout>
    <x-slot name="title">Trash</x-slot>

    @include('partials.breadcrumbs')

    @include('partials.trash-list', ['items' => $trashedItems])
</x-app-layout>