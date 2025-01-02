<header>
    <table class="table mb-2 w-full table-auto">
        <tr>
            <td class="w-full"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="250" /></td>
            <td class="text-center" style="width: 85%">
                <h4 class="text-center text-xl font-bold leading-none">SURAT PERINTAH RAWAT INAP</h4>
                <p class="text-center leading-none">RSIA Aisyiyah Pekajangan</p>
            </td>
        </tr>
    </table>
</header>

<main class="mt-4">
    <table class="table w-full table-auto">
        <tr class="align-top">
            <td class="leading-8" colspan="3">
                <p class="leading-none">Kepada Yth :</p>
                <p class="leading-none">{{ $spri->nm_dokter_bpjs }}</p>
            </td>
            <td style="width:320px;">
                <div class="mb-2">
                    <h5 class="text-nowrap whitespace-nowrap text-sm font-bold leading-none">No. {{ $spri->no_surat }}</h5>
                    <p class="text-nowrap whitespace-nowrap text-sm leading-none">Tgl. {{ $spri->tgl_rencana }}</p>
                </div>

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
            'No. Kartu'     => $sep->no_kartu,
            'Nama Pasien'   => $pasien->nm_pasien,
            'Tgl. Lahir'    => $pasien->tgl_lahir,
            'Diagnosa Awal' => $spri->diagnosa,
            'Tgl. Entry'    => $spri->tgl_surat,
        ] as $key => $value)
            <tr class="align-top">
                <td class="text-nowrap whitespace-nowrap leading-5">{{ $key }}</td>
                <td class="leading-5" style="width: 15px">:</td>
                <td class="leading-5">{{ $value }}</td>
                <td class="leading-5"></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr class="align-top">
            <td class="leading-none" colspan="3">
                <div class="span mt-3">Demikian Atas Bantuannya diucapkan banyak terimakasih.</div>
            </td>
            <td></td>
        </tr>
        <tr class="align-bottom">
            <td class="w-full text-left" colspan="3">
                <div class="text-sm">Tgl. Cetak <?= date('d-m-Y', strtotime($sep->tglsep)) . ' ' . date('H:i:s') ?></div>
            </td>
            <td class="w-full text-center">
                <img src="{{ $barcodeDPJP }}" alt="barcode DPJP" style="width: 150px; height: 150px;"/>
                <p class="mt-2">{{ $spri->nm_dokter_bpjs }}</p>
            </td>
        </tr>
    </table>
</main>