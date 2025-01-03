<header>
    <table class="table mb-2 w-full table-auto">
        <tr>
            <td class="w-full"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="250" /></td>
            <td class="text-center" style="width: 85%">
                <h4 class="text-center text-xl font-bold leading-none">SURAT RENCANA KONTROL</h4>
                <p class="text-center leading-none">RSIA Aisyiyah Pekajangan</p>
            </td>
        </tr>
    </table>

    {{-- <table class="table mb-2 w-full table-auto">
        <tr>
            <td class="w-min"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="250" /></td>
            <td class="p-2 text-center">
                <h5 class="text-nowrap whitespace-nowrap text-base font-bold leading-none">SURAT RENCANA KONTROL</h5>
                <p class="text-nowrap whitespace-nowrap text-sm leading-none">RSIA Aisyiyah Pekajangan</p>
            </td>
            <td class="p-2 text-left">
                <h5 class="text-nowrap whitespace-nowrap text-sm font-bold leading-none">No. {{ $srk->no_surat ?? "-" }}</h5>
                <p class="text-nowrap whitespace-nowrap text-sm leading-none">Tgl. {{ $srk->tgl_rencana ?? "-" }}</p>
            </td>
        </tr>
    </table> --}}
</header>

<main class="mt-5">
    <table class="table w-full table-auto">
        <tr class="align-top">
            <td class="text-nowrap whitespace-nowrap" colspan="3">
                <p class="leading-none">Kepada Yth</p>
                <p class="leading-none ml-3 mt-2">{{ $srk->nm_dokter_bpjs ?? '-' }}</p>
            </td>
            <td style="width:320px;">
                <div class="mb-2">
                    <h5 class="text-nowrap whitespace-nowrap text-sm font-bold leading-none">No. {{ $srk->no_surat }}</h5>
                    <p class="text-nowrap whitespace-nowrap text-sm leading-none">Tgl. {{ $srk->tgl_rencana }}</p>
                </div>

                @if ($srk && $srk->no_surat)
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($srk->no_surat, 'C128') }}" alt="barcode" class="h-10 w-auto" />
                @endif
            </td>
        </tr>
        <tr class="align-top">
            <td class="leading-8 mb-3" colspan="4">Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</td>
        </tr>
        @foreach ([
            'No. Kartu'     => $sep->no_kartu,
            'Nama Pasien'   => $pasien->nm_pasien,
            'Tgl. Lahir'    => $pasien->tgl_lahir,
            'Diagnosa Awal' => $sep->nmdiagnosaawal,
            'Tgl. Entry'    => $srk->tgl_surat,
        ] as $key => $value)
            <tr>
                <td class="text-nowrap whitespace-nowrap leading-5">{{ $key }}</td>
                <td class="leading-5">:</td>
                <td class="text-nowrap whitespace-nowrap leading-5">{{ $value }}</td>
                <td class="leading-5"></td>
            </tr>
        @endforeach
        <tr class="align-top">
            <td class="leading-none" colspan="4">
                <div class="span mt-5">Demikian Atas Bantuannya diucapkan banyak terimakasih.</div>
            </td>
        </tr>
        <tr class="align-bottom">
            <td class="w-full text-left" colspan="3">
                <div class="text-sm">Tgl. Cetak <?= date('d-m-Y', strtotime($sep->tglsep)) . ' ' . date('H:i:s') ?></div>
            </td>
            <td class="w-full text-center">
                <img src="{{ $barcodeDPJP }}" alt="barcode DPJP" style="width: 150px; height: 150px;"/>
                <div class="mt-2">{{ $srk->nm_dokter_bpjs }}</div>
            </td>
        </tr>
    </table>
</main>