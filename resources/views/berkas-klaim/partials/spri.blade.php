<x-print-layout>
    @push('header')
        <header>
            <table class="table mb-2 w-full table-auto">
                <tr>
                    <td class="w-min"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="250" /></td>
                    <td class="p-2 text-center">
                        <h5 class="text-nowrap whitespace-nowrap text-base font-bold leading-none">SURAT PERINTAH RAWAT INAP</h5>
                        <p class="text-nowrap whitespace-nowrap text-sm leading-none">RSIA Aisyiyah Pekajangan</p>
                    </td>
                    <td class="p-2 text-left">
                        <h5 class="text-nowrap whitespace-nowrap text-sm font-bold leading-none">No. {{ $spri?->no_surat }}</h5>
                        <p class="text-nowrap whitespace-nowrap text-sm leading-none">Tgl. {{ $spri?->tgl_rencana }}</p>
                    </td>
                </tr>
            </table>
        </header>
    @endpush

    @php
        $QRText = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $spri?->nm_dokter_bpjs . '. ID : ' . $sep?->dokter?->pegawai?->sidikjari?->sdk;
    @endphp

    <main style="margin-top: 30px">
        <table class="table w-full table-auto">
            <tr class="align-top">
                <td class="text-nowrap whitespace-nowrap">Kepada Yth</td>
                <td class="px-2" style="width: 5px"></td>
                <td class="text-nowrap whitespace-nowrap">{{ $spri?->nm_dokter_bpjs }}</td>
                <td class="pl-2" style="width:250px;">
                    @if ($spri && $spri->no_surat)
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($spri->no_surat, 'C128') }}" alt="barcode" class="h-10 w-auto" />
                    @endif
                </td>
            </tr>
            <tr class="align-top">
                <td class="leading-8" colspan="3">Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</td>
                <td></td>
            </tr>
            @foreach ([
                'No. Kartu' => $sep?->no_kartu,
                'Nama Pasien' => $pasien?->nm_pasien,
                'Tgl. Lahir' => $pasien?->tgl_lahir,
                'Diagnosa Awal' => $spri?->diagnosa,
                'Tgl. Entry' => $spri?->tgl_surat,
            ] as $key => $value)
              <tr>
                  <td class="text-nowrap whitespace-nowrap leading-5">{{ $key }}</td>
                  <td class="leading-5">:</td>
                  <td class="text-nowrap whitespace-nowrap leading-5">{{ $value }}</td>
                  <td class="leading-5"></td>
              </tr>
            @endforeach
            <tr class="align-top">
                <td class="leading-none" colspan="3">
                  <div class="span mt-3">Demikian Atas Bantuannya diucapkan banyak terimakasih.</div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"><br /></td>
            </tr>
            <tr>
                <td class="w-full text-left" colspan="3">
                    <div class="text-sm">Tgl. Cetak <?= date('d-m-Y', strtotime($sep?->tglsep)) . ' ' . date('H:i:s') ?></div>
                </td>
                <td class="w-full text-center">
                    <div class="relative inline-block h-28 w-28">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRText, 'QRCODE') }}" alt="barcode" class="h-2w-28 w-28" />
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-9 w-9" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                    </div>
                    <div class="mt-2">{{ $spri?->nm_dokter_bpjs }}</div>
                </td>
            </tr>
        </table>
    </main>
</x-print-layout>
