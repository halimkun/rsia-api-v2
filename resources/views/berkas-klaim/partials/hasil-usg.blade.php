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
                                                HASIL PEMERIKSAAN USG
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
                                'No. RM'     => $sep->nomr,
                                'Nama'       => $pasien->nm_pasien,
                                'Umur'       => $regPeriksa->umurdaftar . ' ' . $regPeriksa->sttsumur,
                                'Tgl. Lahir' => $sep->tanggal_lahir,
                                'Alamat'     => $pasien->alamat,
                                'No. HP'     => $pasien->no_tlp,
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
        $QRText = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $regPeriksa->dokter->nm_dokter . '. ID : ' . \App\Helpers\SafeAccess::object($regPeriksa, "dokter->sidikjari->sidikjari");
    @endphp

    <main>
        <table class="table w-full border" style="border-color: #333;">
            <tr>
                <td colspan="3" class="px-2 py-1">
                    <p class="leading-5 text-sm">
                        {!! nl2br($usg->catatan) !!}  
                    </p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-center" style="max-width: 280px">
                    <div class="relative inline-block h-28 w-28">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRText, 'QRCODE') }}" alt="barcode" class="h-2w-28 w-28" />
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-9 w-9" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                    </div>
                    <div class="mt-2">{{ $regPeriksa->dokter->nm_dokter }}</div>
                </td>
            </tr>
        </table>
    </main>
</x-print-layout>