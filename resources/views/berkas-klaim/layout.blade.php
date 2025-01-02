<x-print-layout>
    {{-- this layout will has pages data loop them and print show the html inside --}}
    @foreach ($pages as $index => $html)
        {!! $html !!}
        
        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</x-print-layout>