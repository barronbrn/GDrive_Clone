<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @include('partials.dashboard-files', [
        'folders' => $folders ?? collect(), 
        'files' => $files ?? collect(),
        'folder' => $folder ?? null,
        'breadcrumbs' => $breadcrumbs ?? collect()
    ])
</x-app-layout>