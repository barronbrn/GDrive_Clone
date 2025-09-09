<!-- Breadcrumb Navigation -->
<nav class="flex items-center text-sm font-medium text-gray-500 mb-6">
    <a href="{{ Route::has('file.index') ? route('file.index') : '/' }}" class="hover:text-bri-blue transition-colors">Dashboard</a>
    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
        @foreach ($breadcrumbs as $breadcrumb)
            <span class="mx-2 text-gray-400">/</span>
            @if(isset($breadcrumb['route']))
                <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue transition-colors">{{ $breadcrumb['name'] }}</a>
            @else
                <span>{{ $breadcrumb['name'] }}</span>
            @endif
        @endforeach
    @endif
</nav>
