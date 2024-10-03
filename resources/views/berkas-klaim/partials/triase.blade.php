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
                                                TRIASE GAWAT DARURAT
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
                                'No. RM'     => $regPeriksa?->no_rkm_medis,
                                'Nama'       => $regPeriksa?->pasien?->nm_pasien,
                                'Umur'       => $regPeriksa?->umurdaftar . ' ' . $regPeriksa?->sttsumur,
                                'Tgl. Lahir' => $regPeriksa?->pasien?->tgl_lahir,
                                'Alamat'     => $regPeriksa?->pasien?->alamat,
                                'No. HP'     => $regPeriksa?->pasien?->no_tlp,
                            ] as $key => $value)
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
            <thead>
                <tr>
                    <td class="border px-2 py-1 text-left leading-none " colspan="6" style="border-color: #333;">
                        <span class="text-bold">Tanggal : </span> {{ $triase?->tgl_kunjungan }}
                    </td>
                </tr>
                <tr>
                    <td class="border py-1 text-center leading-none " style="background-color: #FFFFFF; border-color: #333;">
                        Prioritas Waktu Tunggu
                    </td>
                    <td class="border py-1 text-center leading-none  {!! !$triase?->skala1->isEmpty() ? 'font-bold' : '' !!}" style="background-color: #F87171; border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! $triase?->skala1->isEmpty() ? '&#9744;' : '&#9745;' !!}</span> ATS I <br>
                        Segera
                    </td>
                    <td class="border py-1 text-center leading-none  {!! !$triase?->skala2->isEmpty() ? 'font-bold' : '' !!}" style="background-color: #FBBF24; border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! $triase?->skala2->isEmpty() ? '&#9744;' : '&#9745;' !!}</span> ATS II <br>
                        10 Menit
                    </td>
                    <td class="border py-1 text-center leading-none  {!! !$triase?->skala3->isEmpty() ? 'font-bold' : '' !!}" style="background-color: #34D399; border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! $triase?->skala3->isEmpty() ? '&#9744;' : '&#9745;' !!}</span> ATS III <br>
                        30 Menit
                    </td>
                    <td class="border py-1 text-center leading-none  {!! !$triase?->skala4->isEmpty() ? 'font-bold' : '' !!}" style="background-color: #60A5FA; border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! $triase?->skala4->isEmpty() ? '&#9744;' : '&#9745;' !!}</span> ATS IV <br>
                        60 Menit
                    </td>
                    <td class="border py-1 text-center leading-none  {!! !$triase?->skala5->isEmpty() ? 'font-bold' : '' !!}" style="background-color: #FFFFFF; border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! $triase?->skala5->isEmpty() ? '&#9744;' : '&#9745;' !!}</span> ATS V <br>
                        120 Menit
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border py-1 text-center leading-none  px-1" style="border-color: #333;">Jalan Nafas</td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala1, 'jalan nafas', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala1, 'jalan nafas') !!}</span> Obstruksi
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'jalan nafas', true) ? 'font-bold' : '' !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'jalan nafas') !!}</span> Paten
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'jalan nafas', true) ? 'font-bold' : '' !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'jalan nafas') !!}</span> Paten
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'jalan nafas', true) ? 'font-bold' : '' !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'jalan nafas') !!}</span> Paten
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'jalan nafas', true) ? 'font-bold' : '' !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'jalan nafas') !!}</span> Paten
                    </td>
                </tr>

                <tr>
                    <td class="border py-1 text-center leading-none  px-1" style="border-color: #333;">Pernapasan</td>
                    <td class="border py-1 leading-none  px-1" style="border-color: #333;">
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Distres Napas Berat', true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Distres Napas Berat') !!}</span>
                            Distres Napas Berat
                        </div>    
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Henti Napas',true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Henti Napas') !!}</span>
                            Henti Napas
                        </div>    
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Hipoventilasi', true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'pernapasan', 'Hipoventilasi') !!}</span>
                            Hipoventilasi
                        </div>    
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'pernapasan', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'pernapasan') !!}</span> Distres Napas Sedang
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'pernapasan', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'pernapasan') !!}</span> Distres Napas Ringan
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'pernapasan', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'pernapasan') !!}</span> Tidak Ada Distres Napas
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'pernapasan', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'pernapasan') !!}</span> Tidak Ada Distres Napas
                    </td>
                </tr>

                <tr>
                    <td class="border py-1 text-center leading-none  px-1" style="border-color: #333;">Sirkulasi</td>
                    <td class="border py-1 leading-none  px-1" style="border-color: #333;">
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Gangguan Hemodinamik Berat', true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Gangguan Hemodinamik Berat') !!}</span> Gangguan Hemodinamik Berat
                        </div>    
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Henti Jantung', true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Henti Jantung') !!}</span> Henti Jantung
                        </div>    
                        <div class="leading-none {!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Pendarahan Tidak Terkontrol', true) ? "font-bold" : "" !!}">
                            <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::multiCheckPemeriksaaan($triase?->skala1, 'sirkulasi', 'Pendarahan Tidak Terkontrol') !!}</span> Pendarahan Tidak Terkontrol
                        </div>    
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'sirkulasi', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'sirkulasi') !!}</span> Gangguan Hemodinamik Sedang
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'sirkulasi', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'sirkulasi') !!}</span> Gangguan Hemodinamik Ringan
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'sirkulasi', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'sirkulasi') !!}</span> Tidak Ada Gangguan Sirkulasi
                    </td>
                    <td class="border py-1 leading-none  px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'sirkulasi', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'sirkulasi') !!}</span> Tidak Ada Gangguan Sirkulasi
                    </td>
                </tr>

                <tr>
                    <td class="border py-1 text-center leading-none px-1" style="border-color: #333;">GCS</td>
                    <td class="border py-1 leading-none px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala1, 'gcs', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala1, 'gcs') !!}</span> GCS < 9
                    </td>
                    <td class="border py-1 leading-none px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'gcs', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala2, 'gcs') !!}</span> GCS 9 - 12 
                    </td>
                    <td class="border py-1 leading-none px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'gcs', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala3, 'gcs') !!}</span> GCS > 12
                    </td>
                    <td class="border py-1 leading-none px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'gcs', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala4, 'gcs') !!}</span> Normal GCS
                    </td>
                    <td class="border py-1 leading-none px-1 {!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'gcs', true) ? "font-bold" : "" !!}" style="border-color: #333;">
                        <span class="font-dejavu text-lg leading-none">{!! \App\Helpers\TriaseHelper::cekPemeriksaan($triase?->skala5, 'gcs') !!}</span> Normal GCS
                    </td>
                </tr>

                <tr class="align-middle">
                    <td class="border text-center leading-none px-1 py-5" colspan="3" style="border-color: #333;">
                        {{ $triase?->tgl_kunjungan }}
                    </td>
                    <td class="border text-center leading-none px-1 py-5" colspan="3" style="border-color: #333;">
                        <?php $QRDokter = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $regPeriksa?->dokter?->nm_dokter . '. ID : ' . $regPeriksa?->dokter->sidikjari?->sidikjari; ?>
                        
                        <div class="relative inline-block h-28 w-28">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRDokter, 'QRCODE') }}" alt="barcode" class="w-28 h-28" />
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                        </div>

                        <div class="mt-1">
                            {{ $regPeriksa?->dokter?->nm_dokter }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</x-print-layout>
