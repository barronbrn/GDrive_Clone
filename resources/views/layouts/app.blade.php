<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - DataBOX</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --bri-blue: #00529B; --bri-blue-dark: #003a70; }
        body { background-color: #f4f7fc; }
        .bg-bri-blue { background-color: var(--bri-blue); }
        .hover\:bg-bri-blue-dark:hover { background-color: var(--bri-blue-dark); }
        .text-bri-blue { color: var(--bri-blue); }
        .ring-bri-blue:focus { --tw-ring-color: var(--bri-blue); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased" 
      x-data="{ 
          showCreateFolderModal: false, 
          showUploadFileModal: false,
          showEditModal: false,
          editItem: {} 
      }">

    <div class="flex h-screen bg-gray-100">
        {{-- Memanggil Sidebar --}}
        @include('layouts.partials.sidebar')

        <main class="flex-1 p-8 overflow-y-auto">
            {{-- Memanggil Header --}}
            @include('layouts.partials.header')

            <div class="mt-8">
                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                
                {{-- Di sinilah konten spesifik halaman akan ditampilkan --}}
                {{ $slot }}
            </div>
        </main>
    </div>

    <div x-show="showCreateFolderModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        {{-- ... Kode modal Create Folder ... --}}
    </div>
    <div x-show="showUploadFileModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        {{-- ... Kode modal Upload File ... --}}
    </div>
    <div x-show="showEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        {{-- ... Kode modal Edit Name ... --}}
    </div>
</body>
</html>