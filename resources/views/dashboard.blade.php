<x-app-layout>
    <x-slot name="title">
        @if(isset($folder) && $folder) {{ $folder->name }} 
        @else Dashboard 
        @endif
    </x-slot>

    @if(isset($folder) && $folder)
        @include('partials.folder-contents')
    @else
        @include('partials.dashboard-files')
    @endif
</x-app-layout>