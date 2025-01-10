@foreach ($labs as $key => $lab)
    <?php $frlab = $lab->first(); ?>

    <header>
        <table class="table w-full border-b" style="border-bottom: 1px solid #333;">
            <tr>
                <td style="width: 60px" class="p-2 py-4 text-center align-middle">
                    <img src="{{ public_path('assets/images/logo.png') }}" width="60" />
                </td>
                <td class="p-2 py-4 text-center">
                    <h2 class="text-center text-lg font-bold leading-none text-gray-800">Rumah Sakit Ibu Dan Anak Aisyiyah Pekajangan</h2>
                    <p class="mt-1 text-sm leading-none">Jalan Raya Pekajangan No. 610, Pekalongan, 51172<br>Telp. (0285) 785909 Email : rba610@gmail.com<br>Website : www.rsiaaisyiyah.com</p>
                </td>
            </tr>
        </table>
    </header>

    <main>
        <div class="text-center">
            <h4 class="text-lg font-bold">HASIL PEMERIKSAAN LABORATORIUM</h4>
        </div>

        <div class="mt-3">
            <table class="table w-full">
                <tr class="align-top">
                    <td>
                        <table class="table w-full">
                            @foreach ([
								'No. RM'      => $regPeriksa->no_rkm_medis,
								'Nama Pasien' => \Str::title($pasien->nm_pasien),
								'JK / Umur'   => $pasien->jk . ' / ' . $regPeriksa->umurdaftar . ' ' . $regPeriksa->sttsumur,
								'Alamat'      => \Str::title($pasien->alamat),
								'No. Periksa' => \App\Helpers\SafeAccess::object($frlab, 'no_rawat'),
							] as $key => $val)
                                <tr class="align-top">
                                    <th class="text-left text-nowrap whitespace-nowrap leading-5">{{ $key }}</th>
                                    <td class="px-2 leading-5">:</td>
                                    <td class="w-full leading-5">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table class="table w-full">
                            @foreach ([
								'Penanggung Jawab' => \App\Helpers\SafeAccess::object($frlab, 'dokter->nm_dokter'),
								'Dokter Pengirim'  => \App\Helpers\SafeAccess::object($frlab, 'perujuk->nm_dokter'),
								'Tgl. Pemeriksaan' => \App\Helpers\SafeAccess::object($frlab, 'tgl_periksa'),
								'Jam pemeriksaan'  => \App\Helpers\SafeAccess::object($frlab, 'jam'),
								'Poli'             => \App\Helpers\SafeAccess::object($regPeriksa, 'poliklinik->nm_poli'),
							] as $key => $val)
                                <tr class="align-top">
                                    <th class="text-left text-nowrap whitespace-nowrap leading-5">{{ $key }}</th>
                                    <td class="px-2 leading-5">:</td>
                                    <td class="w-full leading-5">{{ $val }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="mt-3">
            <table class="table w-full">
                <thead>
                    <tr class="border-b border-t text-center align-middle" style="background-color: lightgoldenrodyellow; border-top: 0.5px solid #333; border-bottom: 0.5px solid #333;">
                        <th class="py-1 leading-none">Pemeriksaan</th>
                        <th class="py-1 leading-none">Hasil</th>
                        <th class="py-1 leading-none">Satuan</th>
                        <th class="py-1 leading-none">Nilai Rujukan</th>
                        <th class="py-1 leading-none">Keterangan</th>
                    </tr>
                </thead>
                @foreach ($lab as $sk1 => $sv1)
                    @php
                        $tglCetak = $sv1->tgl_periksa . ' ' . $sv1->jam;
                    @endphp

                    <tr class="border-b align-middle" style="border-bottom-color: #313131;">
                        <td colspan="5" class="py-1 font-bold leading-none">{{ $sv1->jenisPerawatan->nm_perawatan }}</td>
                    </tr>

                    @foreach ($sv1->detailPeriksaLab as $sk2 => $sv2)
                        <tr class="border-b align-middle" style="border-bottom-color: #a5a5a5;">
                            <td class="py-1 leading-none"><span class="ml-3">{{ $sv2->template->Pemeriksaan }}</span></td>
                            <td class="py-1 text-center leading-none"><span class="ml-3">{{ $sv2->nilai }}</span></td>
                            <td class="py-1 text-center leading-none"><span class="ml-3">{{ $sv2->template->satuan }}</span></td>
                            <td class="py-1 text-center leading-none"><span class="ml-3">{{ $sv2->nilai_rujukan }}</span></td>
                            <td class="py-1 text-center leading-none"><span class="ml-3">{{ $sv2->keterangan }}</span></td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>

        @php
            $QRPetugas = \App\Helpers\SignHelper::rsia($frlab->pegawai->nama, $frlab->pegawai->nik);
            $QRDokter  = \App\Helpers\SignHelper::rsia($frlab->dokter->nm_dokter, $frlab->dokter->kd_dokter);
        @endphp

        <div class="mt-5">
            <table class="table w-full">
                <tr>
                    <td class="text-center">
                        <div class="text-base leading-none">&nbsp;</div>
                        <div class="mb-2">Penanggung Jawab</div>
                        <img src="{{ $QRDokter->getDataUri() }}" alt="QR Dokter Penanggung Jawab" style="width: 150px; height: 150px;"/>
                        <div class="mt-2">{{ $frlab->dokter->nm_dokter }}</div>
                    </td>
                    <td class="text-center">
                        @if ($tglCetak)
                        <div class="text-base leading-none">Tgl. Cetak : {{ date('d/m/Y H:i:s', strtotime($tglCetak)) }}</div>
                        @endif
                        <div class="mb-2">Petugas Laboratorium</div>
                        <img src="{{ $QRPetugas->getDataUri() }}" alt="QR Petugas Lab" style="width: 150px; height: 150px;"/>
                        <div class="mt-2">{{ $frlab->pegawai->nama }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="mt-5">
                            <p class="leading-none">
                                <span class="font-bold">Catatan :</span> <br>
                                Jika ada keragu-raguan pemeriksaan, <br>
                                diharapkan segera menghubungi laboratorium
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </main>

    @if (!$loop->last)
		<div style="page-break-after: always;"></div>
	@endif
@endforeach