<header>
    <table class="table mb-2 w-full table-auto">
        <tr>
            <td class="w-full"><img src="{{ public_path('assets/images/logo-bpjs.png') }}" width="250" /></td>
            <td class="text-center" style="width: 85%">
                <h4 class="text-center text-xl font-bold leading-none">SURAT ELEGIBILITAS PESERTA</h4>
                <p class="text-center leading-none">RSIA Aisyiyah Pekajangan</p>
            </td>
        </tr>
    </table>
</header>

<main class="mt-4">
    <table class="table mt-2 w-full table-auto">
        <tr class="align-top">
            <td class="w-full pr-2">
                <table class="table w-full table-auto">
                    <tbody>
                        @foreach ([
                        'No. SEP'        => $sep->no_sep ?? '-',
                        'Tgl. SEP'       => $sep->tglsep ?? '-',
                        'No. Kartu'      => $sep->no_kartu ?? '-',
                        'Nama Peserta'   => $pasien->nm_pasien ?? '-',
                        'Tgl. Lahir'     => $sep->tanggal_lahir ?? '-',
                        'No. Telepon'    => $sep->notelep ?? '-',
                        'Sub/Spesialis'  => $sep->nmpolitujuan ?? '-',
                        'Dokter'         => $sep->nmdpdjp ?? '-',
                        'Faskes Perujuk' => $sep->nmppkrujukan ?? '-',
                        'Diagnosa Awal'  => $sep->nmdiagnosaawal ?? '-',
                        'Catatan'        => '-',
                        ] as $col => $val)
                        <tr class="align-top">
                            <td class="text-base leading-5">
                                <p class="text-nowrap whitespace-nowrap">{{ $col }}</p>
                            </td>
                            <td class="text-base leading-5">
                                <p class="px-1">:</p>
                            </td>
                            <td class="text-base leading-5">
                                {{ $val }}

                                @if ($col == 'No. Kartu')
                                (MR : {{ \App\Helpers\SafeAccess::object($sep, 'nomr', '-') }})
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td class="pl-2" style="width: 85%">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sep->no_sep ?? 0, 'C128') }}" alt="barcode"
                    class="h-10 w-auto" />
                <table class="table mt-2 w-full table-auto">
                    <tbody>
                        @foreach ([
                        'No.Rawat'      => $sep->no_rawat ?? '-',
                        'No.Reg'        => $regPeriksa->no_reg ?? '-',
                        'Peserta'       => $sep->peserta ?? '-',
                        'Jns Rawat'     => $sep->jnspelayanan == '1' ? 'Rawat Inap' : 'Rawat Jalan',
                        'Jns Kunjungan' => $sep->tujuankunjungan == 0 ? 'Konsultasi Dokter (pertama)' : 'Kunjungan Kontrol(ulangan)',
                        ''              => '',
                        'Poli Perujuk'  => '-',
                        'Kelas Hak'     => 'Kelas ' . $sep->klsrawat,
                        'Kelas Rawat'   => $sep->klsnaik ? \App\Helpers\NaikKelasHelper::translate($sep->klsnaik) : '-',
                        'Penjamin'      => $sep->pembiayaan ? \App\Helpers\NaikKelasHelper::getPembiayaan($sep->pembiayaan) : '-',
                        ] as $col => $val)
                        <tr class="align-top">
                            <td class="text-base leading-5"><span class="text-nowrap whitespace-nowrap">{{ $col
                                    }}</span></td>
                            <td class="text-base leading-5"><span class="px-1">{{ $col == '' ? '' : ':' }}</span>
                            </td>
                            <td class="text-base leading-5">
                                {{-- if $col is empty, then print separator --}}
                                @if ($col == '')
                                <div class="py-1" />
                                @else
                                {{ $val }}
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
                    <td class="pl-1 leading-none">Saya menyetujui BPJS Kesehatan menggunakan infomasi medis pasien
                        jika diperlukan.</td>
                </tr>
                <tr class="align-top text-xs italic">
                    <td class="text-right">*</td>
                    <td class="pl-1 leading-none">SEP Bukan sebagai bukti penjaminan peserta</td>
                </tr>
                <tr class="align-top text-xs italic">
                    <td class="text-right">**</td>
                    <td class="pl-1 leading-none">Dengan tampilnya luaran SEP elektronik ini merupakan hasil
                        validasi terhadap eligibilitas Pasien secara elektronik (validasi finger print atau
                        biometrik / sistem validasi lain) dan selanjutnya Pasien dapat mengakses pelayanan kesehatan
                        rujukan sesuai ketentuan berlaku. Kebenaran dan keaslian atas informasi data Pasien menjadi
                        tanggung jawab penuh FKRTL</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div class="pt-3 text-xs italic">
                            Cetakan ke-1
                            <?= date('d-m-Y', strtotime($sep->tglsep)) . ' ' . date('H:i:s') ?>
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
            <tr class="align-middle">
                <td colspan="2" style="border-color: #333" class="border p-1 px-2 whitespace-nowrap text-nowrap">
                    DIISI DIAGNOSA DAN TINDAKAN DENGAN LENGKAP JELAS DAN TERBACA</td>
                <td style="border-color: #333; width: 15px; !important" class="border p-1 px-2 text-center">KODE
                </td>
            </tr>
            <tr class="align-middle">
                <td style="border-color: #333; width:170px; max-width: 200px" class="border p-1 px-2">DIAGNOSA UTAMA
                </td>
                <td style="border-color: #333" class="border p-1 px-2">1. {{ $diagnosa->where('prioritas',
                    1)->first()->penyakit->nm_penyakit ?? '-' }}</td>
                <td style="border-color: #333" class="border p-1 px-2 text-center">{{ $diagnosa->where('prioritas',
                    1)->first()->kd_penyakit ?? '-' }}</td>
            </tr>

            @foreach ($diagnosa->sortBy('prioritas')->where('prioritas', '<>', 1) as $k => $d)
                <tr class="align-middle">
                    @if ($loop->first)
                    <td style="border-color: #333; width:170px; max-width: 200px" class="border p-1 px-2"
                        rowspan="{{ $diagnosa->where('prioritas', '<>', 1)->count() }}">DIAGNOSA SEKUNDER</td>
                    @endif
                    <td style="border-color: #333" class="border p-1 px-2">{{ ($loop->iteration + 1) }}. {{
                        $d->penyakit->nm_penyakit ?? '-' }}</td>
                    <td style="border-color: #333" class="border p-1 px-2 text-center">{{ $d->kd_penyakit ?? '-' }}
                    </td>
                </tr>
                @endforeach

                @foreach ($prosedur as $k => $p)
                <tr class="align-middle">
                    @if ($loop->first)
                    <td style="border-color: #333; width:170px; max-width: 200px" class="border p-1 px-2"
                        rowspan="{{ $prosedur->count() }}">TINDAKAN</td>
                    @endif
                    <td style="border-color: #333" class="border p-1 px-2">{{ $loop->iteration }}. {{
                        $p->penyakit->deskripsi_panjang ?? '-' }}</td>
                    <td style="border-color: #333" class="border p-1 px-2 text-center">{{ $p->kode ?? '-' }}</td>
                </tr>
                @endforeach
        </table>
    </div>

    <div class="mt-1">
        <table>
            <tr>
                <td>CATATAN :</td>
            </tr>
            <tr>
                <td class="inline-table overflow-visible">
                    <div class="m-0 overflow-visible p-0 leading-none">
                        <span class="font-dejavu">&#9744;</span> <span class="pl-1">Rujukan Terlampir</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-3">
        <table class="w-full">
            <tr class="align-middle">
                <td class="w-full justify-center text-center align-middle">
                    <p class="mb-2">Pasien</p>
                    <img src="{{ $barcodePasien }}" alt="barcode pasien" style="width: 150px; height: 150px;"/>
                    <p>{{ \Str::title($pasien->nm_pasien) ?? '-' }}</p>
                </td>
                <td class="relative w-full justify-center text-center align-middle">
                    <p class="mb-2">Dokter</p>
                    <img src="{{ $barcodeDPJP }}" alt="barcode DPJP" style="width: 150px; height: 150px;"/>
                    <p>{{ \App\Helpers\SafeAccess::object($sep, 'nmdpdjp', '-') }}</p>
                </td>

            </tr>
        </table>
    </div>
</main>