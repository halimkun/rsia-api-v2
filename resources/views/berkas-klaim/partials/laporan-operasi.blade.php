<x-print-layout>
    @push('styles')
        <style>
            @page {
                /* meaning top, right, bottom, left */
                margin: 175px 50px 50px 50px;
            }

            @page :first {
                margin-top: 50px;
                /* Custom margin for the first page */
            }

            header {
                position: fixed;
                top: -10px;
                left: 0px;
                right: 0px;
                height: 50px;
                max-height: min-content !important;
            }

            footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                background-color: lightblue;
                height: 50px;
            }
        </style>
    @endpush

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
                                'No. RM' => $regPeriksa?->no_rkm_medis,
                                'Nama' => $regPeriksa?->pasien?->nm_pasien,
                                'Umur' => $regPeriksa?->umurdaftar . ' ' . $regPeriksa?->sttsumur,
                                'Tgl. Lahir' => $regPeriksa?->pasien?->tgl_lahir,
                                'Alamat' => $regPeriksa?->pasien?->alamat,
                                'No. HP' => $regPeriksa?->pasien?->no_tlp,
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

        <main style="margin-top: 125px;">
            <table class="table w-full table-auto">
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Dokter Ahli Bedah :</p>
                        <p class="text-nowrap whitespace-nowrap">{{ $operasi?->detailOperator1?->nm_dokter }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Asisten :</p>
                        <p class="text-nowrap whitespace-nowrap">{{ $operasi?->detailAsistenOperator1?->nama }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Perawat :</p>
                        <p class="text-nowrap whitespace-nowrap">{{ $operasi?->detailAsistenOperator2?->nama }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Dokter Ahli Anestesi :</p>
                        <p>{{ $operasi?->detailDokterAnestesi?->nm_dokter }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Jenis Anestesi :</p>
                        <p>{{ $operasi?->jenis_anestesi }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Diagnosa Pre Operatif :</p>
                        <p>{{ $operasi?->diagnosa_preop }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Diagnosa Post Operatif :</p>
                        <p>{{ $operasi?->diagnosa_postop }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Jumlah darah masuk transfusi :</p>
                        <ul class="ml-4">
                            <li>{{ $operasi?->darah_masuk }}</li>
                        </ul>

                        <p class="font-bold">Jumlah darah yang hilang :</p>
                        <ul class="ml-4">
                            <li>{{ $operasi?->darah_hilang }}</li>
                        </ul>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Komplikasi :</p>
                        <p>{{ $operasi?->komplikasi }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Jaringan yang di-Eksis/insisi :</p>
                        <p>{{ $operasi?->jaringan_insisi }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="2">
                        <p class="font-bold">Pemeriksa PA :</p>
                        <p>{{ $operasi?->pemeriksaan_pa }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Tanggal Operasi :</p>
                        <p>{{ explode(' ', $operasi?->tgl_operasi)[0] }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Jam Mulai Operasi :</p>
                        <p>{{ explode(' ', $operasi?->tgl_operasi)[1] }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Jam Selesai Operasi :</p>
                        <p>{{ explode(' ', $operasi?->tgl_selesai)[1] }}</p>
                    </td>
                    <td class="border px-1 align-top" style="border: 1px solid #333;">
                        <p class="font-bold">Lama Operasi :</p>
                        @php
                            $start = new DateTime($operasi?->tgl_operasi);
                            $end = new DateTime($operasi?->tgl_selesai);
                            $diff = $start?->diff($end);
                        @endphp

                        @if ($diff?->h > 0)
                            <p>{{ $diff?->format('%H jam %i menit %s detik') }}</p>
                        @else
                            <p>{{ $diff?->format('%i menit %s detik') }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="4">
                        <p class="font-bold">Nama/ Macam Operasi :</p>
                        <p>{{ $operasi?->detailPaket?->nm_perawatan }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="border px-1 align-top" style="border: 1px solid #333;" colspan="4">
                        <p class="mb-3 font-bold">Laporan Jalannya Operasi :</p>
                        <p class="leading-4">{!! nl2br($operasi?->laporan_operasi) !!}</p>
                    </td>
                </tr>
            </table>

            <div>
                <div class="float-right mt-8 text-center">
                    @php
                        $QRDokter = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $operasi?->detailOperator1?->nm_dokter . '. ID : ' . $operasi?->detailOperator1?->sidikjari?->sidikjari;
                    @endphp

                    <div class="relative inline-block h-28 w-28">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRDokter, 'QRCODE') }}" alt="barcode" class="h-28 w-28" />
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                    </div>

                    <p>{{ $operasi?->detailOperator1?->nm_dokter }}</p>
                </div>
            </div>
        </main>
    @endpush
</x-print-layout>
