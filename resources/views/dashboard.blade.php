<x-app-layout>
    {{-- Mengatur judul halaman dinamis --}}
    <x-slot name="title">
        @if(isset($folder) && $folder) {{ $folder->name }} 
        @elseif(request()->routeIs('recent')) Recent Items
        @elseif(request()->routeIs('trash')) Trash
        @else Dashboard 
        @endif
    </x-slot>

    {{-- Memanggil partial yang sesuai untuk halaman ini --}}
    @if(isset($folder) && $folder)
        @include('partials.folder-contents', ['items' => $items, 'folder' => $folder, 'breadcrumbs' => $breadcrumbs])
    @else
        @include('partials.dashboard-files', ['recentItems' => $recentItems ?? collect(), 'items' => $items ?? collect()])
    @endif
</x-app-layout>