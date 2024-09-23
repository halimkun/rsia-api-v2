<x-print-layout>
  <div class="w-full h-auto">
    @php
      $basePath = rtrim($path ?? 'http://192.168.100.33/erm/public/erm/', '/');
    @endphp
    <img src="{{ $basePath }}/{{ $image }}" alt="{{ $alt ?? 'Berkas Pendukung' }}" class="w-full" style="max-height: 30cm !important;" onerror="this.onerror=null; this.src='{{ $basePath }}/default-image.png'; alert('Failed to load berkas pendukung');">
  </div>
</x-print-layout>