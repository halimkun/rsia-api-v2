<x-print-layout>
    @push('styles')
    <style>
        @page {
            /* size 210 mm x 330 mm */
            size: 210mm 330mm portrait;
            margin: 100px 0px 200px 0px;
        }

        main {
            margin: 0px 19mm;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            width: 100%;
        }

        footer {
            position: fixed;
            bottom: -200px;
            left: 0;
            right: 0;
            width: 100%;
        }
    </style>
    @endpush

    @push('header')
    <header>
        <img src="{{ asset('assets/images/kop-surat/header.png') }}" alt="Kop Header" />
    </header>
    @endpush

    @push('footer')
    <footer>
        <img src="{{ asset('assets/images/kop-surat/footer.png') }}" alt="Kop footer" />
    </footer>
    @endpush

    <main>
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

        <div class="mt-4">
            <p class="text-xs italic leading-none" style="color: gray">Dokumen ini dibuat secara otomatis oleh sistem
            </p>
            <p class="text-xs italic leading-none" style="color: gray">Semua bukti kehadiran yang tertera diatas adalah
                benar dan tidak bisa diubah</p>
        </div>

        {{-- daftar karyawan hadir loop $penerima --}}
        <table class="mt-4 w-full">
            <tr class="border-b-2 font-bold p-2" style="background-color: #f0f0f0;">
                <td class="border-b-2 px-2">No</td>
                <td class="border-b-2 px-2">Nama</td>
                <td class="border-b-2 px-2">Jabatan</td>
                <td class="border-b-2 px-2">Timestamp</td>
            </tr>
            @foreach ($penerima as $item)
            <tr>
                <td class="border-b-2 px-2">{{ $loop->iteration }}.</td>
                <td class="border-b-2 px-2">{{ $item->detail->nama }}</td>
                <td class="border-b-2 px-2">{{ $item->detail->jbtn }}</td>
                <td class="border-b-2 px-2">{{ $kehadiran->where('nik', $item->penerima)->first()->created_at ?? "-" }}
                </td>
            </tr>
            @endforeach
        </table>
    </main>
</x-print-layout>