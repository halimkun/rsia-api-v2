
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
                                    <td class="border text-center align-middle font-bold leading-none py-1" style="border-color: #333;">
                                        LAPORAN OPERASI
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
                        'No. RM'     => \App\Helpers\SafeAccess::object($regPeriksa, 'no_rkm_medis', '-'),
                        'Nama'       => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->nm_pasien', '-'),
                        'Umur'       => \App\Helpers\SafeAccess::object($regPeriksa, 'umurdaftar', '-') . ' ' . \App\Helpers\SafeAccess::object($regPeriksa, 'sttsumur', '-'),
                        'Tgl. Lahir' => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->tgl_lahir', '-'),
                        'Alamat'     => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->alamat', '-'),
                        'No. HP'     => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->no_tlp', '-'),
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

@foreach ($data as $operasi)
<main class="mt-5">
    <table class="table w-full table-auto">
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Dokter Ahli Bedah :</p>
                <p class="text-nowrap whitespace-nowrap">{{ \App\Helpers\SafeAccess::object($operasi, 'detailOperator1->nm_dokter', '-') }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Asisten :</p>
                <p class="text-nowrap whitespace-nowrap">{{ \App\Helpers\SafeAccess::object($operasi, 'detailAsistenOperator1->nama', '-') }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Perawat :</p>
                <p class="text-nowrap whitespace-nowrap">{{ \App\Helpers\SafeAccess::object($operasi, 'detailAsistenOperator2->nama', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Dokter Ahli Anestesi :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'detailDokterAnestesi->nm_dokter', '-') }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Jenis Anestesi :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'jenis_anestesi', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Diagnosa Pre Operatif :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'diagnosa_preop', '-') }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Diagnosa Post Operatif :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'diagnosa_postop', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Jumlah darah masuk transfusi :</p>
                <ul class="ml-4">
                    <li>{{ \App\Helpers\SafeAccess::object($operasi, 'darah_masuk', '-') }}</li>
                </ul>

                <p class="font-bold">Jumlah darah yang hilang :</p>
                <ul class="ml-4">
                    <li>{{ \App\Helpers\SafeAccess::object($operasi, 'darah_hilang', '-') }}</li>
                </ul>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Komplikasi :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'komplikasi', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Jaringan yang di-Eksis/insisi :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'jaringan_insisi', '-') }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                <p class="font-bold">Pemeriksa PA :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'pemeriksaan_pa', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Tanggal Operasi :</p>
                <p>{{ explode(' ', \App\Helpers\SafeAccess::object($operasi, 'tgl_operasi', ''))[0] }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Jam Mulai Operasi :</p>
                <p>{{ explode(' ', \App\Helpers\SafeAccess::object($operasi, 'tgl_operasi', ''))[1] }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Jam Selesai Operasi :</p>
                <p>{{ explode(' ', \App\Helpers\SafeAccess::object($operasi, 'tgl_selesai', ''))[1] }}</p>
            </td>
            <td class="border px-1 align-top" style="border: 1px solid #333;">
                <p class="font-bold">Lama Operasi :</p>
                @php
                    $start = new DateTime($operasi->tgl_operasi);
                    $end = new DateTime($operasi->tgl_selesai);
                    $diff = $start->diff($end);
                @endphp

                @if ($diff->h > 0)
                    <p>{{ $diff->format('%H jam %i menit %s detik') }}</p>
                @else
                    <p>{{ $diff->format('%i menit %s detik') }}</p>
                @endif
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="4">
                <p class="font-bold">Nama/ Macam Operasi :</p>
                <p>{{ \App\Helpers\SafeAccess::object($operasi, 'detailPaket.nm_perawatan', '-') }}</p>
            </td>
        </tr>
        <tr>
            <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="4">
                <p class="mb-3 font-bold">Laporan Jalannya Operasi :</p>
                <p class="leading-4">{!! nl2br(\App\Helpers\SafeAccess::object($operasi, 'laporan_operasi', '-')) !!}</p>
            </td>
        </tr>
    </table>

    <div>
        <div class="float-right mt-8 text-center">
            <img src="{{ $barcodeDPJP }}" alt="barcode DPJP" style="width: 150px; height: 150px;"/>
            <p>{{ \App\Helpers\SafeAccess::object($operasi, 'detailOperator1->nm_dokter') }}</p>
        </div>
    </div>
</main>
@endforeach
