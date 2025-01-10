@foreach ($files as $key => $item)
    <div class="w-full h-auto">
        @php
            $basePath = rtrim($path ?? 'http://192.168.100.33/erm/public/erm/', '/');
        @endphp

        <img 
            class="w-full"
            src="{{ $basePath }}/{{ $item }}" 
            alt="{{ $alt ?? 'Berkas Pendukung' }}" 
            style="max-height: 30cm !important;" 
            onerror="this.onerror=null; this.src='{{ $basePath }}/default-image.png'; alert('Failed to load berkas pendukung');"
        >
    </div>
@endforeach