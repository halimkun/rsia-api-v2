<x-print-layout>
    @push('header')
        <header>
            <table class="table mb-2 w-full table-auto">
                <tr>
                    <td class="w-full">
                        <table>
                            <tr>
                                <td style="width: 60px" class="p-2 text-center align-middle">
                                    <img src="{{ public_path('assets/images/logo.png') }}" width="60" />
                                </td>
                                <td class="p-2 text-center">
                                    <h2 class="text-center text-base font-bold leading-none text-gray-800">RUMAH SAKIT IBU DAN ANAK AISYIYAH</h2>
                                    <h2 class="text-center text-base font-bold leading-none text-gray-800">PEKAJANGAN - PEKALONGAN</h2>
                                    <p class="mt-1 text-sm leading-none">Jalan Raya Pekajangan No. 610, Pekalongan, 51172<br>Telp. (0285) 785909 Email : rba610@gmail.com<br>Website : www.rsiaaisyiyah.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <table class="w-full">
                                        <tr>
                                            <td class="border text-center align-middle font-bold" style="border-color: #333;">
                                                RESUME MEDIS
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="border text-center" style="width: 70%; border-color: #333;">
                        <table class="table w-full table-auto">
                            @foreach ([
                                'No. RM'     => \App\Helpers\SafeAccess::object($sep, 'nomr'),
                                'Nama'       => \App\Helpers\SafeAccess::object($pasien, 'nm_pasien'),
                                'Umur'       => \App\Helpers\SafeAccess::object($regPeriksa, 'umurdaftar') . ' ' . \App\Helpers\SafeAccess::object($regPeriksa, 'sttsumur'),
                                'Tgl. Lahir' => \App\Helpers\SafeAccess::object($sep, 'tanggal_lahir'),
                                'Alamat'     => \App\Helpers\SafeAccess::object($pasien, 'alamat'),
                                'No. HP'     => \App\Helpers\SafeAccess::object($pasien, 'no_tlp'),
                            ] as $key => $value)
                                <tr>
                                    <td class="text-nowrap whitespace-nowrap px-1 text-left text-sm leading-4">{{ $key }}</td>
                                    <td class="px-1 text-left text-sm leading-4" style="width: 1px;">:</td>
                                    <td class="w-full px-1 text-left text-sm leading-4">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </header>
    @endpush

    @php
        if (!$kamarInap->isEmpty()) {
            $tglKeluar = $kamarInap[0]->tgl_keluar;
            $jamKeluar = $kamarInap[0]->jam_keluar;

            $masuk = \Carbon\Carbon::parse($regPeriksa->tgl_registrasi);
            $keluar = \Carbon\Carbon::parse($tglKeluar);

            $los = $keluar->diffInDays($masuk) + 1;

        } else {
            $tglKeluar = null;
            $jamKeluar = null;
            $los = null;
        }

        $QRDokter = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . \App\Helpers\SafeAccess::object($sep, 'nmdpdjp') . '. ID : ' . \App\Helpers\SafeAccess::object($ttdDpjp, 'sidikjari->sidikjari');
        $QRKoor = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . \App\Helpers\SafeAccess::object($ttdResume, 'nama') . '. ID : ' . \App\Helpers\SafeAccess::object($ttdResume, 'sidikjari->sidikjari');
    @endphp

    <main>
        <table class="table m-0 mt-2 w-full table-auto">
            <tr>
                <td colspan="2" class="border p-1" style="border-color: #333;">
                    <table class="table table-auto">
                        @foreach ([
                            'Tanggal Masuk'  => \App\Helpers\SafeAccess::object($regPeriksa, 'tgl_registrasi'),
                            'Tanggal Keluar' => $tglKeluar,
                            'Lama Rawat'     => $los . ' Hari',
                            'Ruang Rawat'    => !$kamarInap->isEmpty() ? $kamarInap[0]->kamar->bangsal->nm_bangsal : '-',
                        ] as $k => $v)
                            <tr>
                                <td class="text-nowrap whitespace-nowrap leading-none">{{ Str::title($k) }}</td>
                                <td class="px-1 leading-none" style="width: 3px;">:</td>
                                <td class="w-full leading-none">{{ $v }}</td>

                                @if ($k == 'Tanggal Masuk')
                                    <td class="text-nowrap whitespace-nowrap leading-none">Jam</td>
                                    <td class="px-1 leading-none" style="width: 3px;">:</td>
                                    <td class="leading-none">{{ $regPeriksa->jam_reg }}</td>
                                @endif

                                @if ($k == 'Tanggal Keluar')
                                    <td class="text-nowrap whitespace-nowrap leading-none">Jam</td>
                                    <td class="px-1 leading-none" style="width: 3px;">:</td>
                                    <td class="leading-none">{{ $jamKeluar }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td class="border p-1" style="border-color: #333;">
                    <table class="table table-auto">
                        @foreach ([
                            'Cara Bayar'     => \App\Helpers\SafeAccess::object($regPeriksa, 'caraBayar->png_jawab', '-'),
                            'Indikasi Rawat' => \App\Helpers\SafeAccess::object($resume, 'alasan', '-'),
                            'Diagnosa Awal'  => \App\Helpers\SafeAccess::object($resume, 'diagnosa_awal', '-'),
                            'DPJP'           => \App\Helpers\SafeAccess::object($sep, 'nmdpdjp', '-'),
                        ] as $k => $v)
                            <tr>
                                <td class="text-nowrap whitespace-nowrap leading-none">{{ $k }}</td>
                                <td class="px-1 leading-none" style="width: 3px;">:</td>
                                <td class="w-full leading-none">{{ $v }}</td>

                                @if ($k == 'Tanggal Masuk')
                                    <td class="text-nowrap whitespace-nowrap leading-none">Jam</td>
                                    <td class="px-1 leading-none" style="width: 3px;">:</td>
                                    <td class="leading-none">{{ $regPeriksa->jam_reg }}</td>
                                @endif

                                @if ($k == 'Tanggal Keluar')
                                    <td class="text-nowrap whitespace-nowrap leading-none">Jam</td>
                                    <td class="px-1 leading-none" style="width: 3px;">:</td>
                                    <td class="leading-none">-</td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">ANAMNESIS</p>
                    <p class="leading-4">{!! nl2br($resume->keluhan_utama ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">PEMERIKSAAN FISIK</p>
                    <p class="leading-4">{!! nl2br($resume->pemeriksaan_fisik ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">PEMERIKSAAN PENUNJANG</p>
                    <p class="leading-4">{!! nl2br($resume->hasil_laborat ?? null) !!}</p>
                    <p class="leading-4">{!! nl2br($resume->pemeriksaan_penunjang ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">DIAGNOSA AKHIR</p>
                    <table class="w-full">
                        <tr>
                            <td width="150" style="width: 150px !important;"></td>
                            <td></td>
                            <td></td>
                            <td width="70" class="font-bold">ICD-10</td>
                        </tr>
                        <tr>
                            <td>Diagnosa Utama</td>
                            <td>:</td>
                            <td>{{ $resume->diagnosa_utama ?? null }}</td>
                            <td>{{ $resume->kd_diagnosa_utama ?? null }}</td>
                        </tr>
                        <tr>
                            <td>Diagnosa Sekunder</td>
                            <td>:</td>
                            <td>1. {{ $resume->diagnosa_sekunder ?? null }}</td>
                            <td>{{ $resume->kd_diagnosa_sekunder ?? null }}</td>
                        </tr>

                        @for ($i = 2; $i <= 7; $i++)
                            @php
                                $diagnosa = 'diagnosa_sekunder' . $i;
                                $kd_diagnosa = 'kd_diagnosa_sekunder' . $i;
                            @endphp
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $i }}. {{ $resume->$diagnosa ?? null }}</td>
                                <td>{{ $resume->$kd_diagnosa ?? null }}</td>
                            </tr>
                        @endfor
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">TINDAKAN / OPERASI</p>
                    <table class="w-full">
                        <tr>
                            <td width="150" style="width: 129px !important;"></td>
                            <td></td>
                            <td></td>
                            <td width="70" class="font-bold">ICD-9-CM</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>1. {{ $resume->prosedur_utama ?? null }}</td>
                            <td>{{ $resume->kd_prosedur_utama ?? null }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>1. {{ $resume->prosedur_sekunder ?? null }}</td>
                            <td>{{ $resume->kd_prosedur_sekunder ?? null }}</td>
                        </tr>

                        @for ($i = 2; $i <= 3; $i++)
                            @php
                                $prosedur = 'prosedur_sekunder' . $i;
                                $kd_prosedur = 'kd_prosedur_sekunder' . $i;
                            @endphp

                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $i }}. {{ $resume->$prosedur ?? null }}</td>
                                <td>{{ $resume->$kd_prosedur ?? null }}</td>
                            </tr>
                        @endfor
                    </table>

                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">PENGOBATAN / TERAPI</p>
                    <p class="leading-4">{!! nl2br($resume->obat_di_rs ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">PROGNOSIS</p>
                    <p class="leading-4">{!! nl2br($resume->ket_keadaan ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">KONDISI PULANG</p>
                    <table class="table w-full">
                        {{-- TOOD : Sesuaikan dengan kondisi pulang pasien dari database --}}
                        <tr>
                            <td class="m-0 p-0 leading-none"><span class="font-dejavu text-xl leading-none">{!! $resume->cara_keluar == "Atas Izin Dokter" && $resume->keadaan == "Membaik" ? '&#9726;' : '&#9725;' !!}</span> Membaik</td>
                            <td class="m-0 p-0 leading-none"><span class="font-dejavu text-xl leading-none">{!! $resume->cara_keluar == "Pulang Atas Permintaan Sendiri" && $resume->keadaan == "Membaik" ? '&#9726;' : '&#9725;' !!}</span> Pulang Atas Permintaan Sendiri</td>
                            <td class="m-0 p-0 leading-none"><span class="font-dejavu text-xl leading-none">{!! $resume->keadaan == "Meninggal" ? '&#9726;' : '&#9725;' !!}</span> Meninggal</td>
                            <td class="m-0 p-0 leading-none"><span class="font-dejavu text-xl leading-none">{!! $resume->cara_keluar == "Pindah RS" ? '&#9726;' : '&#9725;' !!}</span> Rujuk</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">OBAT PULANG</p>
                    <p class="leading-4">{!! nl2br($resume->obat_pulang ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">EDUKASI</p>
                    <p class="leading-4">{!! nl2br($resume->edukasi ?? null) !!}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">SHK</p>
                    <table>
                        <tr>
                            <td>{{ $resume->shk ?? null }}</td>
                            <td>{{ $resume->shk_keterangan ?? null }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <p class="mb-1 font-bold leading-none">INSTRUKSI TINDAK LANJUT</p>
                    <table>
                        <tr>
                            <td>
                                @php
                                    switch ($resume->dilanjutkan ?? null) {
                                        case 'Kembali Ke RS':
                                            $text = 'Kontrol';
                                            break;
                                        case 'Puskesmes':
                                        case 'Dokter Luar':
                                            $text = 'Dilanjutkan ke FKTP';
                                            break;
                                        case 'RS Lain':
                                            $text = 'Rujuk RS Lain';
                                            break;
                                        default:
                                            $text = 'Lainnya';
                                    }
                                @endphp

                                {{ $text }}
                            </td>
                            <td>:</td>
                            {{-- <td>{{ \Carbon\Carbon::parse($resume->kontrol)->isoFormat('dddd, D MMMM Y') }}</td> --}}
                            <td>{{ $resume->kontrol ? \Carbon\Carbon::parse($resume->kontrol)->format('d-m-Y') : '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="3" class="border p-1" style="border-color: #333;">
                    <table class="table w-full">
                        <tr>
                            <td class="w-full text-center"></td>
                            <td class="w-full text-center"></td>
                            <td class="w-full text-center">Pekalongan,</td>
                        </tr>
                        <tr>
                            <td class="mb-3 w-full text-center">Kepala Ruang</td>
                            <td class="mb-3 w-full text-center">Pasien/Keluarga</td>
                            <td class="text-nowrap mb-3 w-full whitespace-nowrap text-center">Dokter Penanggung Jawab Pelayanan</td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div class="relative inline-block h-28 w-28">
                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRKoor, 'QRCODE') }}" alt="barcode" class="w-28 h-28" />
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                                </div>
                            </td>
                            <td class="text-center">
                                @if ($ttdPasien && $ttdPasien->verifikasi)
                                    <img src="http://192.168.100.31/rsiap/file/verif_sep/{{ $ttdPasien->verifikasi }}" width="70%" />
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="relative inline-block h-28 w-28">
                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRDokter, 'QRCODE') }}" alt="barcode" class="w-28 h-28" />
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">{{ $ttdResume->nama ?? "-" }}</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ $sep->nmdpdjp ?? "-" }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </main>
</x-print-layout>
