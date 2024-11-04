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
                                                PENILAIAN AWAL MEDIS GAWAT DARURAT
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
                                'No. RM'        => SafeAccess::object($regPeriksa, 'no_rkm_medis', '-'),
                                'Nama'          => SafeAccess::object($regPeriksa, 'pasien->nm_pasien', '-'),
                                'Umur'          => (SafeAccess::object($regPeriksa, 'umurdaftar', '-') . ' ' . SafeAccess::object($regPeriksa, 'sttsumur', '-')),
                                'Tgl. Lahir'    => SafeAccess::object($regPeriksa, 'pasien->tgl_lahir', '-'),
                                'Alamat'        => SafeAccess::object($regPeriksa, 'pasien->alamat', '-'),
                                'No. HP'        => SafeAccess::object($regPeriksa, 'pasien->no_tlp', '-'),
                            ] as $key           => $value)
                                <tr class="align-top">
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

    <main>
        <table class="table w-full">
            <tbody>
                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <table class="w-full">
                            <tr class="align-top">
                                <td class="leading-none" style="width: 70px;">Tanggal</td>
                                <td class="leading-none">:</td>
                                <td class="leading-none">{{ $asmed->tanggal }}</td>
                            </tr>
                            <tr class="align-top">
                                <td class="leading-none" style="width: 70px;">Anamnesis</td>
                                <td class="leading-none">:</td>
                                <td class="leading-none">{{ $asmed->anamnesis }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">I. RIWAYAT KESEHATAN</p>
                        <table class="w-full">
                            <tr class="align-top">
                                <td class="leading-none" style="width: 200px;">Keluhan Utama</td>
                                <td class="px-2 leading-none" style="width: 7px;">:</td>
                                <td class="m-0 p-0 leading-none">{{ $asmed->keluhan_utama }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-2 leading-none">Riwayat Penyakit Sekarang : </p>
                        <p class="leading-none">{{ $asmed->rps }}</p>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="3" style="border-color: #333;">
                        <p class="mb-2 leading-none">Riwayat Penyakit Dahulu : </p>
                        <p class="leading-none">{{ $asmed->rpd }}</p>
                    </td>
                    <td class="border px-2 py-1" colspan="3" style="border-color: #333;">
                        <p class="mb-2 leading-none">Riwayat Penyakit Dalam Pengobatan : </p>
                        <p class="leading-none">{{ $asmed->rpk }}</p>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="3" style="border-color: #333;">
                        <p class="mb-2 leading-none">Riwayat Pengobatan : </p>
                        <p class="leading-none">{{ $asmed->rpo }}</p>
                    </td>
                    <td class="border px-2 py-1" colspan="3" style="border-color: #333;">
                        <p class="mb-2 leading-none">Riwayat Alergi : </p>
                        <p class="leading-none">{{ $asmed->alergi }}</p>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">II. PEMERIKSAAN FISIK</p>
                        <table class="w-full">
                            <tr class="align-top">
                                <td class="w-full leading-none">Keadaan Umum : {{ $asmed->keadaan }}</td>
                                <td class="w-full leading-none">Kesadaran : {{ $asmed->kesadaran }}</td>
                                <td class="leading-none" style="width: 50%;">GCS (E,V,M) : {{ $asmed->gcs }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="align-top">
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-2 leading-none">Tanda Vital : </p>
                        <table class="w-full">
                            <tr class="align-top">
                                <td class="w-full leading-none">TD : {{ $asmed->td }}</td>
                                <td class="w-full leading-none">N : {{ $asmed->nadi }}</td>
                                <td class="w-full leading-none">R : {{ $asmed->rr }}</td>
                                <td class="w-full leading-none">S : {{ $asmed->suhu }}</td>
                                <td class="w-full leading-none">SPO : {{ $asmed->spo }}</td>
                                <td class="w-full leading-none">BB : {{ $asmed->bb }}</td>
                                <td class="w-full leading-none">TB : {{ $asmed->tb }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="align-middle">
                    <td class="w-full border px-2 py-1" colspan="2" style="border-color: #333">
                        <table>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Kepala</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->kepala }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Mata</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->mata }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Gigi & Mulut</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->gigi }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Leher</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->leher }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-full border px-2 py-1" colspan="2" style="border-color: #333">
                        <table>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Thoraks</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->thoraks }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Abdomen</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->abdomen }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Genital & Anus</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->genital }}</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap pl-1 font-semibold underline">Ekstremitas</td>
                                <td>:</td>
                                <td class="pr-2">{{ $asmed->ekstremitas }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="border px-2 py-1" colspan="2" style="border-color: #333; width: 400px;">
                        <p class="">{!! $asmed->ket_fisik !!}</p>
                    </td>
                </tr>

                <tr>
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">III. STATUS LOKALIS</p>
                        <p class="leading-none">Keterangan : </p>
                        <p class="leading-none">{{ $asmed->ket_lokalis }}</p>
                    </td>
                </tr>

                <tr>
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">IV. PEMERIKSAAN PENUNJANG</p>
                        <table class="table w-full">
                            <tr>
                                <td>
                                    <p>EKG : </p>
                                    {{ $asmed->ekg }}
                                </td>
                                <td>
                                    <p>Radiologi : </p>
                                    {{ $asmed->rad }}
                                </td>
                                <td>
                                    <p>Laboratorium : </p>
                                    {{ $asmed->lab }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">V. DIAGNOSIS</p>
                        <p class="leading-none">{!! nl2br($asmed->diagnosis) !!}</p>
                    </td>
                </tr>

                <tr>
                    <td class="border px-2 py-1" colspan="6" style="border-color: #333;">
                        <p class="mb-3 font-bold leading-none">VI. TATA LAKSANA</p>
                        <p class="leading-none">{!! nl2br($asmed->tata) !!}</p>
                    </td>
                </tr>

                <tr>
                    <td class="border px-2 py-1 text-center" colspan="3" style="border-color: #333;">
                        {{ $asmed->tanggal }}
                    </td>
                    <td class="border px-2 py-1 text-center" colspan="3" style="border-color: #333;">
                        @php
                            $QRDokter = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $asmed->dokter->nm_dokter . '. ID : ' . $asmed->dokter->sidikjari->sidikjari;
                        @endphp

                        <div class="relative inline-block h-28 w-28">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRDokter, 'QRCODE') }}" alt="barcode" class="h-28 w-28" />
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</x-print-layout>
