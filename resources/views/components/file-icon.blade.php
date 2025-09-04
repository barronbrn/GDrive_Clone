@props(['item'])

@php
    // Default untuk semua item adalah Outlined
    $icon = 'description'; 
    $colorClass = 'text-gray-500';
    $styleClass = 'material-symbols-outlined'; // <-- Gaya default: Outlined

    if ($item->is_folder) {
        $icon = 'folder';
        $colorClass = 'text-yellow-600';
        $styleClass = 'material-symbols'; // <-- Gaya KHUSUS untuk folder: Filled
    } else {
        $extension = strtolower(pathinfo($item->name, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'pdf':
                $icon = 'picture_as_pdf';
                $colorClass = 'text-red-600';
                break;
            case 'xlsx': case 'xls': case 'csv':
                $icon = 'table_chart';
                $colorClass = 'text-green-600';
                break;
            case 'docx': case 'doc':
                $icon = 'article';
                $colorClass = 'text-blue-600';
                break;
            case 'pptx': case 'ppt':
                $icon = 'slideshow';
                $colorClass = 'text-orange-500';
                break;
            case 'zip': case 'rar': case '7z':
                $icon = 'folder_zip';
                $colorClass = 'text-yellow-500';
                break;
            case 'mp4': case 'mov': case 'avi': case 'mkv':
                $icon = 'movie';
                $colorClass = 'text-red-500';
                break;
            case 'mp3': case 'wav': case 'ogg':
                $icon = 'music_note';
                $colorClass = 'text-pink-500';
                break;
            case 'png': case 'jpg': case 'jpeg': case 'gif': case 'svg': case 'webp':
                $icon = 'image';
                $colorClass = 'text-purple-500';
                break;
            case 'txt':
                $icon = 'notes';
                $colorClass = 'text-gray-500';
                break;
            case 'js': case 'php': case 'html': case 'css': case 'json': case 'xml': case 'ipynb':
                $icon = 'code';
                $colorClass = 'text-indigo-600';
                break;
        }
    }
@endphp

{{-- Menampilkan ikon dengan kelas gaya dan warna yang dinamis --}}
<span class="{{ $styleClass }} {{ $colorClass }} align-middle">
    {{ $icon }}
</span>