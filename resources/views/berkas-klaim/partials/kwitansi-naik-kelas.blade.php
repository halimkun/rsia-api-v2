<x-print-layout>
    @push('header')
        <header style="width: 100%; padding: 0 0; margin: 0 0;">
            <table class="table mb-3 w-full table-auto">
                <tr style="text-align: center;">
                    <td style="text-align: center;">
                        <img src="{{ public_path('assets/images/logo.png') }}" width="70" />
                    </td>
                    <td style="text-align: center;">
                        <h3 class="text-center font-bold leading-none">Rumah Sakit Ibu Dan Anak Aisyiyah Pekajangan</h3>
                        <p class="mt-1 text-sm leading-none">Jalan Raya Pekajangan No. 610, Pekalongan, 51172<br>Telp. (0285) 785909 Email : rba610@gmail.com<br>Website : www.rsiaaisyiyah.com</p>
                    </td>
                </tr>
            </table>
        </header>

        {{-- border bottom --}}
        <hr style="border: 1px solid #333; margin: 0 0; padding: 0 0;">
    @endpush

    <main>
        <div class="mb-3 mt-2 text-center font-bold">
            KUITANSI PEMBAYARAN PASIEN BPJS NAIK KELAS
        </div>

        <div class="px-3">
            <table class="table" style="width: 90%;">
                <tr class="align-top">
                    <td class="max-w-min leading-5" style="width: 150px;">No.</td>
                    <td class="px-1 leading-5" style="width: 10px;">:</td>
                    <td class="leading-5">{{ $sep->nomr }}</td>
                </tr>
                <tr class="align-top">
                    <td class="max-w-min leading-5" style="width: 150px;">Telah diterima dari</td>
                    <td class="px-1 leading-5" style="width: 10px;">:</td>
                    <td class="leading-5">{{ $sep->pasien->nm_pasien }}</td>
                </tr>
                <tr class="align-top">
                    <td class="max-w-min leading-5" style="width: 150px;">Diagnosa</td>
                    <td class="px-1 leading-5" style="width: 10px;">:</td>
                    <td class="leading-5">{{ $sep->naikKelas->diagnosa }}</td>
                </tr>
                <tr class="align-top">
                    <td class="max-w-min leading-5" style="width: 150px;">Uang Sebanyak</td>
                    <td class="px-1 leading-5" style="width: 10px;">:</td>
                    <td class="leading-5">Rp {{ number_format($sep->naikKelas->tarif_akhir, 0, ',', '.') }}</td>
                </tr>
                <tr class="align-top">
                    <td class="max-w-min leading-5" style="width: 150px;">Guna Membayar</td>
                    <td class="px-1 leading-5" style="width: 10px;">:</td>
                    <td class="leading-5">
                        Biaya perawatan ranap inap a.n <strong>{{ $sep->pasien->nm_pasien }}</strong> dirawat dari tanggal <i>{{ $sep->reg_periksa->tgl_registrasi }}</i> s/d <i>{{ $kamarInap->first()->tgl_keluar }}</i> <br>dengan rincian :
                    </td>
                </tr>
            </table>

            <table class="table" style="width: 90%;">
                <tr class="align-middle">
                    <td class="text-left"><span class="pr-1">•</span> Biaya perawatan (hak peserta BPJS di Kelas {{ $sep->klsrawat }}) </td>
                    <td class="text-right">Rp {{ number_format($sep->naikKelas->tarif_2 ?? $sep->naikKelas->tarif_1, 0, ',', '.') }}</td>
                </tr>
                <tr class="align-middle">
                    <td class="text-left" colspan="2"><span class="pr-1">•</span> Biaya perawatan di RSIA Aisyiyah Pekajangan (Naik {{ \App\Helpers\NaikKelasHelper::translate($sep->klsnaik) }})</td>
                </tr>
                <tr class="align-middle">
                    <td class="text-right" colspan="2">
                        @if ($sep->naikKelas->presentase)
                            <span class="pr-2">{{ number_format($sep->naikKelas->tarif_1, 0, ',', '.') }} - {{ number_format($sep->naikKelas->tarif_2, 0, ',', '.') }} + ({{ $sep->naikKelas->tarif_1 == $sep->naikKelas->tarif_2 ? number_format($sep->naikKelas->tarif_2, 0, ',', '.') : number_format($sep->naikKelas->tarif_1, 0, ',', '.') }} x {{ $sep->naikKelas->presentase }}%) =</span> Rp {{ number_format($sep->naikKelas->tarif_akhir, 0, ',', '.') }}
                        @else
                            {{ number_format($sep->naikKelas->tarif_1, 0, ',', '.') }} - {{ number_format($sep->naikKelas->tarif_2, 0, ',', '.') }} =
                            Rp {{ number_format($sep->naikKelas->tarif_akhir, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="mt-5"><span class="font-bold">Terbilang : </span>{{ ucwords(\App\Helpers\TerbilangHelper::terbilang($sep->naikKelas->tarif_akhir)) }} rupiah</div>
                    </td>
                </tr>
            </table>

            <table class="w-full mt-8">
                <tr class="align-middle">
                    <td class="text-center w-full">&nbsp;</td>
                    <td class="text-center w-full">&nbsp;</td>
                    <td class="text-center w-full">Pekalongan, {{ \Carbon\Carbon::parse($kamarInap->first()->tgl_keluar)->format('d F Y') }}</td>
                </tr>
                <tr class="align-top">
                    <td class="text-center w-full">
                        <p class="mb-3">Pasien</p>
                        <img src="http://192.168.100.31/rsiap/file/verif_sep/{{ $ttdPasien?->verifikasi }}" width="70%" >
                    </td>
                    <td class="text-center w-full">&nbsp;</td>
                    <td class="text-center w-full">
                        <p class="mb-1">Kasir</p>
                        @php
                            $hash = $kasir->sidikjari ? $kasir->sidikjari->sidikjari : \Hash::make($kasir->nip);
                            $QRPetugas = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $kasir->nama . '. ID : ' . $hash;
                        @endphp

                        <div class="relative inline-block h-28 w-28">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRPetugas, 'QRCODE') }}" alt="barcode" class="h-28 w-28" />
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                        </div>

                        <p class="mt-3">{{ $kasir->nama }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </main>
</x-print-layout>
