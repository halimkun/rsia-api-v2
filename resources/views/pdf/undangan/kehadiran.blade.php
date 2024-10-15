<x-print-layout>
    @push('header')
    @endpush

    <main>

        {{-- line --}}
        <div class="mb-4 border-b-2" style="border-color: #333;"></div>

        {{-- text on center --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold">BUKTI KEHADIRAN</h1>
        </div>

        {{-- Prolog --}}
        <table class="mt-4 w-full">
            <tr>
                <td>Perihal</td>
                <td style="max-width: 10px;">:</td>
                <td>{{ $surat->perihal }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td style="max-width: 10px;">:</td>
                <td>{{ $surat->tempat }}</td>
            </tr>
            <tr>
                <td>Agenda</td>
                <td style="max-width: 10px;">:</td>
                <td>{{ \Carbon\Carbon::parse($surat->tanggal)->translatedFormat('l, d F Y H:i') }}</td>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                <td style="max-width: 10px;">:</td>
                <td>{{ $surat->penanggungJawabSimple->nama }}</td>
            </tr>
        </table>

        {{-- daftar karyawan hadir loop $penerima --}}
        <table class="mt-4 w-full">
            <tr>
                <td class="border-b-2">No</td>
                <td class="border-b-2">Nama</td>
                <td class="border-b-2">Jabatan</td>
                <td class="border-b-2">Timestamp</td>
            </tr>
            @foreach ($penerima as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->detail->nama }}</td>
                    <td>{{ $item->detail->jbtn }}</td>
                    <td>{{ $kehadiran->where('nik', $item->penerima)?->first()?->created_at ?? "-" }}</td>
                </tr>
            @endforeach
        </table>

        <div class="mt-4">
            {{-- tambahkan prof bahwa data yang tertera diatas adalah benar, dan tidak bisa diubah, jelaskan juga bahwa dokumen ini dibuat secara otomatis oleh sistem --}}
            <p class="text-xs italic leading-none" style="color: gray">Dokumen ini dibuat secara otomatis oleh sistem</p>
            <p class="text-xs italic leading-none" style="color: gray">Semua data yang tertera diatas adalah benar dan tidak bisa diubah</p>
        </div>
    </main>
</x-print-layout>
