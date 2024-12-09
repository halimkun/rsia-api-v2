<x-print-layout>
    @push('header')
        <header>
            <table class="table mb-2 w-full table-auto">
                <tr>
                    <td style="width: 100px" class="p-2 text-center align-middle">
                        <img src="{{ public_path('assets/images/logo.png') }}" width="70" />
                    </td>
                    <td class="p-2 text-center">
                        <h2 class="text-center text-base font-bold leading-none text-gray-800">RUMAH SAKIT IBU DAN ANAK AISYIYAH</h2>
                        <h2 class="text-center text-base font-bold leading-none text-gray-800">PEKAJANGAN - PEKALONGAN</h2>
                        <p class="mt-1 text-sm leading-none">Jalan Raya Pekajangan No. 610, Pekalongan, 51172<br>Telp. (0285) 785909 Email : rba610@gmail.com Website : www.rsiaaisyiyah.com</p>
                    </td>
                </tr>
            </table>

            {{-- line --}}
            <hr class="border border-dashed" style="border-color: #333;">

        </header>
    @endpush

    <main class="mt-4">
        {{-- TEXT CPPT --}}
        <p class="text-center text-base font-bold">CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT)</p>

        {{-- Table Identitas --}}
        <table class="mt-4 w-full">
            <tr class="align-middle">
                <td class="border-b border-t font-bold text-lg leading-none py-1" style="border-color: #333, #333; background-color: #f1f1f1;" colspan="4">Nomor Rekam medis</td>
                <td class="border-b border-t font-bold text-lg leading-none py-1" style="border-color: #333, #333; background-color: #f1f1f1;" colspan="3">{{ $regPeriksa->no_rkm_medis }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Nama</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->nm_pasien }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Nama Ibu</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->nm_ibu }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">No. Identitas</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->no_ktp }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Umur</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->diff(\Carbon\Carbon::now())->format('%y T %m B %d H') : "-" }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Agama</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->agama }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Tanggal Lahir</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->translatedFormat('d F Y') : "-" }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Status Periksa</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $regPeriksa->stts }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Jenis Kelamin</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->jk }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Pekerjaan</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->pekerjaan }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Pendidikan</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->pnd }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Alamat</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333" colspan="5">{{ $pasien->alamat }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Nama Keluarga</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->namakeluarga }}</td>
                <td class="border-b border-t" style="border-color: #333, #333"></td>
                <td class="border-b border-t" style="border-color: #333, #333">Hubungan Keluarga</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333">{{ $pasien->keluarga }}</td>
            </tr>

            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333" rowspan="3" colspan="2">Bila Ada sesuatu<br>Menghubungi</td>
                <td class="border-b border-t" style="border-color: #333, #333">Nama</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333" colspan="3">{{ $regPeriksa->p_jawab }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">Alamat</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333; max-width: 10px;" colspan="3">{{ $regPeriksa->almt_pj }}</td>
            </tr>
            <tr class="align-middle">
                <td class="border-b border-t" style="border-color: #333, #333">No. Telp</td>
                <td class="border-b border-t" style="border-color: #333, #333">:</td>
                <td class="border-b border-t" style="border-color: #333, #333" colspan="3">{{ $pasien->no_tlp }}</td>
            </tr>
        </table>

        <table class="mt-4 w-full">
            <thead style="background-color: lightyellow">
                <tr>
                    <th class="border py-1 leading-none" style="border-color: #333">No</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Tanggal</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Suhu(C)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Tensi(mmHg)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Nadi(/menit)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">RR(/menit)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Tinggi(cm)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">Berat(Kg)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">GCS(E,V,M)</th>
                    <th class="border py-1 leading-none" style="border-color: #333">SPO2</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cppt as $item)
                    <tr class="align-top">
                        <td class="border py-1 text-center leading-none" style="border-color: #333;" rowspan="8">{{ $loop->iteration }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;" rowspan="8">{{ $item->tgl_perawatan }} {{ $item->jam_rawat }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->suhu_tubuh }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->tensi }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->nadi }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->respirasi }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->tinggi }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->berat }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->gcs }}</td>
                        <td class="border py-1 text-center leading-none" style="border-color: #333;">{{ $item->spo2 }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Kesadaran</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->kesadaran }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Subyektif</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->keluhan }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Obyektif</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->pemeriksaan }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Assesment</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->penilaian }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Plan</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->rtl }}</td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Instruksi &amp; Evaluasi</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">
                            <div class="mb-3">
                                <p class="leading-none font-bold">Instruksu : </p>
                                <p class="leading-none">{{ $item->instruksi }}</p>
                            </div>

                            <div>
                                <p class="leading-none font-bold">Evaluasi : </p>
                                <p class="leading-none">{{ $item->evaluasi }}</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border py-1 leading-none font-bold px-1" style="border-color: #333;" colspan="2">Alergi</td>
                        <td class="border py-1 leading-none px-1" style="border-color: #333;" colspan="6">{{ $item->alergi }}</td>
                    </tr>
                    <tr>
                        <td class="border px-2 py-1 leading-none" style="border-color: #333;" colspan="4">Dokter / Petugas</td>
                        <td class="border py-1 leading-none" style="border-color: #333;" colspan="6">{{ $item->petugas->nama }}</td>
                    </tr>
                    <tr><td colspan="10"></td></tr>
                @endforeach
            </tbody>
        </table>
    </main>
</x-print-layout>
