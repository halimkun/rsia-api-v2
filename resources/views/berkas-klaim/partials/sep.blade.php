<x-print-layout>
    @push('header')
        <table class="table w-full table-auto mb-10"> 
            <tr>
                <td class="w-full"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="280" /></td>
                <td class="text-center" style="width: 85%">
                    <h4 class="text-center text-xl font-bold leading-none">SURAT ELEGIBILITAS PESERTA</h4>
                    <p class="text-center leading-none">RSIA Aisyiyah Pekajangan</p>
                </td>
            </tr>
        </table>
    @endpush

    <table class="table mt-2 w-full table-auto">
        <tr class="align-top">
            <td class="w-full pr-2">
                <table class="table w-full table-auto">
                    <tbody>
                        @foreach ([
                            'No. SEP' => $sep?->no_sep ?? '-',
                            'Tgl. SEP' => $sep?->tglsep ?? '-',
                            'No. Kartu' => $sep?->no_kartu ?? '-',
                            'Nama Peserta' => $sep?->pasien?->nm_pasien ?? '-',
                            'Tgl. Lahir' => $sep?->tanggal_lahir ?? '-',
                            'No. Telepon' => $sep?->notelep ?? '-',
                            'Sub/Spesialis' => $sep?->nmpolitujuan ?? '-',
                            'Dokter' => $sep?->nmdpdjp ?? '-',
                            'Faskes Perujuk' => $sep?->nmppkrujukan ?? '-',
                            'Diagnosa Awal' => $sep?->nmdiagnosaawal ?? '-',
                            'Catatan' => '-',
                        ] as $col => $val)
                            <tr class="align-top">
                                <td class="text-base leading-5"><span class="text-nowrap whitespace-nowrap">{{ $col }}</span></td>
                                <td class="text-base leading-5"><span class="px-1">:</span></td>
                                <td class="text-base leading-5">
                                    {{ $val }}

                                    @if ($col == 'No. Kartu')
                                        (MR : {{ $sep?->nomr ?? '-' }})
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td class="pl-2" style="width: 85%">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sep->no_sep ?? 0, 'C128') }}" alt="barcode" class="h-10 w-auto" />
                <table class="table mt-2 w-full table-auto">
                    <tbody>
                        @foreach ([
                            'No.Rawat' => $sep?->no_rawat,
                            'No.Reg' => $sep?->reg_periksa?->no_reg ?? '-',
                            'Peserta' => $sep?->peserta,
                            'Jns Rawat' => $sep?->jnspelayanan == '1' ? 'Rawat Inap' : 'Rawat Jalan',
                            'Jns Kunjungan' => $sep?->tujuankunjungan == 0 ? 'Konsultasi Dokter (pertama)' : 'Kunjungan Kontrol(ulangan)',
                            '' => '',
                            'Poli Perujuk' => '-',
                            'Kelas Hak' => 'Kelas ' . $sep?->klsrawat,
                            'Kelas Rawat' => $sep?->klsnaik,
                            'Penjamin' => '',
                        ] as $col => $val)
                            <tr class="align-top">
                                <td class="text-base leading-5"><span class="text-nowrap whitespace-nowrap">{{ $col }}</span></td>
                                <td class="text-base leading-5"><span class="px-1">{{ $col == '' ? '' : ':' }}</span></td>
                                <td class="text-base leading-5">
                                    {{-- if $col is empty, then print separator --}}
                                    @if ($col == '')
                                        <div class="py-1" />
                                    @else
                                        @if ($col == 'Kelas Rawat')
                                            @php
                                                $klsNaik = [
                                                    '1' => 'VVIP',
                                                    '2' => 'VIP',
                                                    '3' => 'Kelas 1',
                                                    '4' => 'Kelas 2',
                                                    '5' => 'Kelas 3',
                                                    '0' => 'Diatas Kelas 1',
                                                ];

                                                echo $klsNaik[$val] ?? '';
                                            @endphp
                                        @else
                                            {{ $val }}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="mt-5">
        <table style="width: 65%">
            <tbody>
                <tr class="align-top text-xs italic">
                    <td class="text-right">*</td>
                    <td class="pl-1 leading-none">Saya menyetujui BPJS Kesehatan menggunakan infomasi medis pasien jika diperlukan.</td>
                </tr>
                <tr class="align-top text-xs italic">
                    <td class="text-right">*</td>
                    <td class="pl-1 leading-none">SEP Bukan sebagai bukti penjaminan peserta</td>
                </tr>
                <tr class="align-top text-xs italic">
                    <td class="text-right">**</td>
                    <td class="pl-1 leading-none">Dengan tampilnya luaran SEP elektronik ini merupakan hasil validasi terhadap eligibilitas Pasien secara elektronik (validasi finger print atau biometrik / sistem validasi lain) dan selanjutnya Pasien dapat mengakses pelayanan kesehatan rujukan sesuai ketentuan berlaku. Kebenaran dan keaslian atas informasi data Pasien menjadi tanggung jawab penuh FKRTL</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div class="pt-3 text-xs italic">
                            Cetakan ke-1 <?= date('d-m-Y', strtotime($sep->tglsep)) . ' ' . date('H:i:s') ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        <div class="text-center">
            <h3 class="text-xl font-bold leading-none">FORMULIR VERIFIKASI BPJS</h3>
            <p class="text-sm leading-none">RSIA ASIYIYAH PEKAJANGAN</p>
        </div>
    </div>

    <div class="mt-2">
        <table class="table w-full table-auto">
            <tr>
                <td colspan="2" style="border-color: #333" class="border p-2">DIISI DIAGNOSA DAN TINDAKAN DENGAN LENGKAP JELAS DAN TERBACA</td>
                <td style="border-color: #333; width: 65px;" class="border p-2 text-center">KODE</td>
            </tr>
            <tr>
                <td style="border-color: #333; width:170px; max-width: 200px" class="border p-2">DIAGNOSA UTAMA</td>
                <td style="border-color: #333" class="border p-2">{{ $diagnosa->where('prioritas', 1)->first()->penyakit->nm_penyakit ?? '-' }}</td>
                <td style="border-color: #333" class="border p-2 text-center">{{ $diagnosa->where('prioritas', 1)->first()->kd_penyakit ?? '-' }}</td>
            </tr>
            <tr>
                <td style="border-color: #333; width:170px; max-width: 200px" class="border p-2">DIAGNOSA SEKUNDER</td>
                <td colspan="2" style="border-color: #333" class="m-0 border p-0">
                    <table class="table w-full">
                        @foreach ($diagnosa->where('prioritas', '<>', 1) as $k => $d)
                            <tr class="align-top">
                                <td class="{{ $loop->last ? '' : 'border-b' }} p-2" style="border-color: #333">
                                    <table>
                                        <tr class="align-top">
                                            <td>{{ $loop->iteration }}. </td>
                                            <td>{{ $d->penyakit->nm_penyakit ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="{{ $loop->last ? '' : 'border-b' }} border-l p-2 text-center" style="width: 65px; border-color: #333">{{ $d->kd_penyakit ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-color: #333; width:170px; max-width: 200px" class="border p-2">TINDAKAN / OPERASI</td>
                <td colspan="2" style="border-color: #333" class="m-0 border p-0">
                    <table class="table w-full">
                        @foreach ($prosedur as $k => $p)
                            <tr class="align-top">
                                <td class="{{ $loop->last ? '' : 'border-b' }} p-2" style="border-color: #333">
                                    <table>
                                        <tr class="align-top">
                                            <td>{{ $loop->iteration }}. </td>
                                            <td>{{ $p->penyakit->deskripsi_panjang ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="{{ $loop->last ? '' : 'border-b' }} border-l p-2 text-center" style="width: 65px; border-color: #333">{{ $p->kode ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-1">
        <table>
            <tr>
                <td>CATATAN :</td>
            </tr>
            <tr>
                <td class="overflow-visible inline-table">
                    <div class="leading-none p-0 m-0 overflow-visible">
                        <input type="checkbox" onclick="return false;"/><span class="pl-2 -mt-2">Rujukan Terlampir</span>
                    </div>
                </td>                
            </tr>
        </table>
    </div>

    @php
        $QRText = "Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh " . $sep?->nmdpdjp . ". ID : " . $sep?->dokter?->pegawai?->sidikjari->sidikjari;
    @endphp

    <div class="mt-3">
        <table class="w-full">
            <tr class="align-middle">
                <td class="w-full text-center align-middle justify-center">
                    <p class="mb-2">Pasien</p>
                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($sep->no_kartu ?? 0, 'QRCODE') }}" alt="barcode" class="w-28 h-2w-28" />
                    <p class="mt-2">{{ $sep?->pasien?->nm_pasien ?? '-' }}</p>
                </td>
                <td class="w-full text-center align-middle justify-center relative">
                    <p class="mb-2">Dokter</p>
                    
                    <div class="relative inline-block w-28 h-28">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRText, 'QRCODE') }}" alt="barcode" class="w-28 h-2w-28" />
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="w-9 h-9" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                    </div>
                    
                    <p class="mt-2">{{ $sep?->nmdpdjp ?? '-' }}</p>
                </td>
                              
            </tr>
        </table>
    </div>
</x-print-layout>
