<x-print-layout>
    @push('header')
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
    @endpush

    <main>
        <div class="my-3 text-center">
            <h4 class="text-lg font-bold">BILLING</h4>
        </div>

        <div class="my-3">
            <table class="table w-full">
                @foreach ([
        'No. Nota' => $nota?->no_nota,
        'No. RM' => $regPeriksa?->no_rkm_medis,
        'Nama Pasien' => $regPeriksa?->pasien?->nm_pasien,
        'JK / Umur' => (\Str::lower($regPeriksa?->pasien?->jk) == 'p' ? 'Perempuan' : 'Laki-laki') . ' / ' . $regPeriksa?->umurdaftar . ' ' . $regPeriksa?->sttsumur,
        'Cara Bayar' => $regPeriksa?->caraBayar?->png_jawab,
    ] as $key => $val)
                    <tr class="align-top">
                        <td class="text-nowrap whitespace-nowrap text-sm leading-4" style="width: 128px;">{{ $key }}</td>
                        <td class="px-2 text-sm leading-4">:</td>
                        <td class="w-full text-sm leading-4">{{ $val }}</td>
                    </tr>
                @endforeach

                @if (\Str::lower($regPeriksa->status_lanjut) == 'ranap')
                    <tr class="align-top">
                        <td class="text-nowrap whitespace-nowrap text-sm leading-4">Bangsal / Kamar</td>
                        <td class="px-2 text-sm leading-4">:</td>
                        <td class="w-full text-sm leading-none">{{ $ruang?->first()?->kamar->bangsal->nm_bangsal }} / {{ $ruang?->first()?->kd_kamar }}</td>
                    </tr>
                @else
                    <tr class="align-top">
                        <td class="text-nowrap whitespace-nowrap text-sm leading-4">Poliklinik</td>
                        <td class="px-2 text-sm leading-4">:</td>
                        <td class="w-full text-sm leading-none">{{ $regPeriksa->poliklinik->nm_poli }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="text-nowrap whitespace-nowrap text-sm leading-4">Dokter</td>
                        <td class="px-2 text-sm leading-4">:</td>
                        <td class="w-full text-sm leading-none">{{ $regPeriksa->dokter->nm_dokter }}</td>
                    </tr>
                @endif

                <tr class="align-top">
                    <td class="whitespace-nowrap text-sm">
                        @if (\Str::lower($regPeriksa->status_lanjut) == 'ranap')
                            Tanggal Perawatan
                        @else
                            Tanggal Periksa
                        @endif
                    </td>
                    <td class="px-2 text-sm leading-4">:</td>
                    <td class="w-full text-sm leading-none">
                        {{ $regPeriksa?->tgl_registrasi . ' ' . $regPeriksa?->jam_reg }}
                        @if (\Str::lower($regPeriksa->status_lanjut) == 'ranap')
                            <span class="font-bold"> s/d </span> {{ $ruang?->first()?->tgl_keluar . ' ' . $ruang?->first()?->jam_keluar }}
                        @endif
                    </td>
                </tr>
                <tr class="align-top">
                    <td class="whitespace-nowrap text-sm">Alamat</td>
                    <td class="px-2 text-sm leading-4">:</td>
                    <td class="w-full text-sm leading-none">{{ $regPeriksa->almt_pj }}</td>
                </tr>
            </table>
        </div>

        @if ($dokters)
            <h4 class="text-base font-bold" style="margin-bottom: 2px">
                &#x2022; Dokter Rawat
            </h4>
            <table class="table mb-5 w-full">
                <thead>
                    <tr style="background-color: lightgoldenrodyellow">
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Keterangan</th>
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333" colspan="2">Nama Dokter</th>
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333" colspan="3">Spesialis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dokters as $kd => $vd)
                        <tr>
                            <td class="px-1 text-sm font-bold leading-none" style="padding: 3px 3px; border-color: #333; width: 150px;">
                                @if ($loop->first)
                                    <span class="px-1 font-bold">Dokter Rawat</span>
                                @endif
                            </td>
                            <td class="border-b px-1 text-sm leading-none" style="padding: 3px 3px; border-color: lightgray; max-width: 250px;" colspan="2">{{ $vd?->first()?->dokter?->nm_dokter }}</td>
                            <td class="border-b px-1 text-sm leading-none" style="padding: 3px 3px; border-color: lightgray; max-width: 250px;" colspan="3">{{ $vd?->first()?->dokter?->spesialis?->nm_sps }}</td>
                        </tr>
                    @endforeach

                    {{-- gap --}}
                    <tr>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="border-color: #333"></td>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="border-color: #333" colspan="5"></td>
                    </tr>
                </tbody>
            </table>
        @endif

        <?php $totalRuang = 0; ?>
        @if ($ruang)
            <h4 class="text-base font-bold" style="margin-bottom: 2px">&#x2022; Ruang Rawat</h4>
            <table class="table mb-5 w-full">
                <thead>
                    <tr style="background-color: lightgoldenrodyellow">
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Keterangan</th>
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Kamar Rawat</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Biaya</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Lama</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Tambahan</th>
                        <th class="whitespace-nowrap border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ruang->groupBy('kd_kamar')->sortKeys() as $k => $v)
                        <tr>
                            <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                                @if ($loop->first)
                                    <span class="font-bold">Kamar Rawat</span>
                                @endif
                            </td>
                            <td class="border-b px-1 text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray; max-width: 250px;">{{ $k }} {{ $v?->first()?->kamar->bangsal->nm_bangsal ?? '' }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ number_format($v?->first()?->trf_kamar ?? 0, 0, ',', '.') }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ $v->sum('lama') }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray"></td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ number_format($v?->first()?->ttl_biaya ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <?php $totalRuang += $v?->first()?->ttl_biaya ?? 0; ?>
                    @endforeach

                    <tr>
                        <td></td>
                        <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey" colspan="4">Total Biaya Ruang</td>
                        <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey">
                            Rp {{ number_format($totalRuang, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- gap --}}
                    <tr>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333"></td>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333" colspan="5"></td>
                    </tr>
                </tbody>
            </table>
        @endif

        <h4 class="text-base font-bold" style="margin-bottom: 2px">&#x2022; Rincian Biaya</h4>
        @foreach ($billing as $noRawat => $items)
            <table class="table mb-5 w-full">
                <thead>
                    <tr style="background-color: lightgoldenrodyellow">
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Keterangan</th>
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Tindakan/Terapi</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Biaya</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Jumlah</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Tambahan</th>
                        <th class="whitespace-nowrap border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalAllkategori = 0; ?>
                    <?php $indexItem = 0;
                    $noKartegori = 1; ?>
                    @foreach ($items as $kategori => $item)
                        <?php $totalKategori = 0; ?>

                        {{-- Looping item --}}
                        @foreach ($item as $k => $v)
                            @if (\Str::contains(\Str::lower($kategori), ['operasi']))
                                <tr>
                                    <td class="relative px-1 text-sm font-bold leading-none" style="padding:3px 3px;border-color: #333; width: 150px;">
                                        @if ($indexItem == 0)
                                            <p class="font-bold" style="position: absolute; top: 3px; left: 4px; transform-origin: top left;">
                                                <span class="mr-1">{{ $noKartegori }}.</span>{{ $kategori }}
                                            </p>
                                        @endif
                                    </td>

                                    <td colspan="5" class="border-b px-1 text-sm font-bold leading-none" style="padding:3px 3px;border-color: lightgray; max-width: 250px;">
                                        {{ $v?->detailPaket?->nm_perawatan }}
                                    </td>
                                </tr>

                                @foreach (json_decode($v, true) as $key => $value)
                                    @if (\Str::contains(\Str::lower($key), ['detail', 'paket']) || $value == 0)
                                        @continue
                                    @endif

                                    <tr>
                                        <td class="px-1 text-sm leading-none" style="padding:3px 3px;"></td>
                                        <td class="border-b px-1 pl-3 text-sm leading-none" style="padding:3px 3px;border-color: lightgray; max-width: 250px;">
                                            {{ \App\Helpers\BillingHelper::determineTarifOperasiTitle($key) }}
                                            <span class="float-right">:</span>
                                        </td>
                                        <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px;border-color: lightgray">{{ number_format($value, 0, ',', '.') }}</td>
                                        <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px;border-color: lightgray">1</td>
                                        <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px;border-color: lightgray">0</td>
                                        <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px;border-color: lightgray">{{ number_format($value, 0, ',', '.') }}</td>
                                    </tr>

                                    <?php $totalKategori += $value; ?>
                                @endforeach

                                @continue
                            @endif

                            <tr>
                                <td class="relative px-1 text-sm font-bold leading-none" style="padding:3px 3px; border-color: #333; width: 150px;">
                                    @if ($indexItem == 0)
                                        <p class="font-bold" style="position: absolute; top: 3px; left: 4px; transform-origin: top left;">
                                            <span class="mr-1">{{ $noKartegori }}.</span>{{ $kategori }}
                                        </p>
                                    @endif
                                </td>

                                <td class="border-b px-1 text-sm leading-none" style="padding:3px 3px; border-color: lightgray; max-width: 250px;">
                                    @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                        {{ $k }}
                                    @else
                                        {{ $v?->first()?->jenisPerawatan?->nm_perawatan ?? 'invalid params' }}
                                    @endif

                                    {{-- float right --}}
                                    <span class="float-right">:</span>
                                </td>

                                <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                    @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                        {{ number_format($v?->first()?->biaya_obat ?? 0, 0, ',', '.') }}
                                    @elseif (\Str::contains(\Str::lower($kategori), ['lab', 'radiologi']))
                                        {{ number_format($v?->first()?->biaya ?? 0, 0, ',', '.') }}
                                    @else
                                        <span class="whitespace-nowrap">{{ number_format($v?->first()?->biaya_rawat ?? 0, 0, ',', '.') }}</span>
                                    @endif
                                </td>

                                <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                    @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                        {{ number_format($v->sum('jml'), 0, ',', '.') }}
                                    @else
                                        {{ number_format($v->count(), 0, ',', '.') }}
                                    @endif
                                </td>

                                <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                    @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                        {{ number_format($v->sum('embalase') + $v->sum('tuslah'), 0, ',', '.') }}
                                    @endif
                                </td>

                                <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                    @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                        {{ number_format($v->sum('total'), 0, ',', '.') }}
                                    @elseif (\Str::contains(\Str::lower($kategori), ['lab', 'radiologi']))
                                        {{ number_format($v->sum('biaya'), 0, ',', '.') }}
                                    @elseif (!\Str::contains(\Str::lower($kategori), ['operasi']))
                                        {{ number_format($v->sum('biaya_rawat'), 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>

                            {{-- Hitung total kategori --}}
                            @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']))
                                <?php $totalKategori += $v->sum('total'); ?>
                            @elseif (\Str::contains(\Str::lower($kategori), ['lab', 'radiologi']))
                                <?php $totalKategori += $v->sum('biaya'); ?>
                            @elseif (!\Str::contains(\Str::lower($kategori), ['operasi']))
                                <?php $totalKategori += $v->sum('biaya_rawat'); ?>
                            @endif

                            {{-- Increment index item --}}
                            <?php $indexItem++; ?>
                        @endforeach

                        {{-- Total Kategori Row --}}
                        <tr>
                            <td></td>
                            <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="background-color: lightgrey" colspan="4">Total Biaya {{ $kategori }}</td>
                            <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="background-color: lightgrey">
                                Rp {{ number_format($totalKategori, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Retur Obat --}}
                        @if (\Str::contains(\Str::lower($kategori), ['obat', 'bhp']) && $returObat->count() > 0)
                            <tr>
                                <td></td>
                                <td colspan="5" class="border-b border-t px-1 text-left text-sm leading-none" style="padding:3px 3px; padding-top: 10px; border-bottom-color: lightgray; border-top-color: #333">
                                    <span class="font-bold">Retur Obat</span>
                                </td>
                            </tr>

                            <?php $totalRetur = 0; ?>
                            @foreach ($returObat as $rok => $rov)
                                <tr>
                                    <td></td>
                                    <td class="border-b px-1 text-left text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                        {{ $rov?->first()?->obat?->nama_brng }}

                                        {{-- float right --}}
                                        <span class="float-right">:</span>
                                    </td>
                                    <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                        {{ number_format($rov?->first()?->h_retur, 0, ',', '.') }}
                                    </td>
                                    <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                        - {{ $rov?->first()?->jml_retur }}
                                    </td>
                                    <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                        0
                                    </td>
                                    <td class="border-b px-1 text-right text-sm leading-none" style="padding:3px 3px; border-color: lightgray">
                                        - {{ number_format($rov?->first()?->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <?php $totalRetur += $rov?->first()?->subtotal ?? 0; ?>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey" colspan="4">Total Retur Obat</td>
                                <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey">
                                    - {{ number_format($totalRetur, 0, ',', '.') }}
                                </td>
                            </tr>
                            <?php $totalKategori -= $totalRetur; ?>
                        @endif

                        {{-- gap --}}
                        <tr>
                            <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="border-color: #333"></td>
                            <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="border-color: #333" colspan="5"></td>
                        </tr>

                        {{-- total semua kategori  --}}
                        <?php $totalAllkategori += $totalKategori; ?>

                        {{-- Reset index item --}}
                        <?php $noKartegori++;
                        $indexItem = 0; ?>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <?php $totalResepPulang = 0; ?>
        @if ($resepPulang)
            <h4 class="text-base font-bold" style="margin-bottom: 2px">&#x2022; Resep Pulang</h4>
            <table class="table mb-5 w-full">
                <thead>
                    <tr style="background-color: lightgoldenrodyellow">
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Keterangan</th>
                        <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Tindakan / Terapi</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Biaya</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Jumlah</th>
                        <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Tambahan</th>
                        <th class="whitespace-nowrap border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resepPulang as $rpk => $rpv)
                        <tr>
                            <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                                @if ($loop->first)
                                    <span class="font-bold">Resep Pulang</span>
                                @endif
                            </td>
                            <td class="border-b px-1 text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray; max-width: 250px;">{{ $rpv?->first()?->obat->nama_brng ?? '' }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ number_format($rpv?->first()?->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ number_format($rpv->sum('jml_barang'), 0, ',', '.') }}</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">0</td>
                            <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ number_format($rpv?->first()?->total ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <?php $totalResepPulang += $rpv?->first()?->total ?? 0; ?>
                    @endforeach

                    <tr>
                        <td></td>
                        <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey" colspan="4">Total Biaya Resep Pulang</td>
                        <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey">
                            Rp {{ number_format($totalResepPulang, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- gap --}}
                    <tr>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333"></td>
                        <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333" colspan="5"></td>
                    </tr>
                </tbody>
            </table>
        @endif

        <h4 class="text-base font-bold" style="margin-bottom: 2px">&#x2022; Tambahan & Potongan Biaya</h4>
        <table class="table mb-5 w-full">
            <thead>
                <tr style="background-color: lightgoldenrodyellow">
                    <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Keterangan</th>
                    <th class="border-b border-t px-1 text-left text-sm leading-none" style="border-color: #333, #333">Tindakan / Terapi</th>
                    <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Biaya</th>
                    <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Jumlah</th>
                    <th class="border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Tambahan</th>
                    <th class="whitespace-nowrap border-b border-t px-1 text-right text-sm leading-none" style="border-color: #333, #333">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalTambahanBiaya = 0; ?>
                @foreach ($tambahanBiaya as $tbk => $tbv)
                    <tr>
                        <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                            @if ($tbk == 0)
                                <span class="font-bold">Tambahan Biaya</span>
                            @endif
                        </td>
                        <td class="border-b px-1 text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray; max-width: 250px;">{{ $tbv->nama_biaya }}</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ $tbv->besar_biaya }}</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">1</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">0</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ $tbv->besar_biaya }}</td>
                    </tr>

                    <?php $totalTambahanBiaya += $tbv->besar_biaya ?? 0; ?>
                @endforeach

                <tr>
                    <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                        @if ($tambahanBiaya->count() == 0)
                            <span class="font-bold">Tambahan Biaya</span>
                        @endif
                    </td>
                    <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey" colspan="4">Total Tambahan Biaya</td>
                    <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey">
                        Rp {{ number_format($totalTambahanBiaya, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- gap --}}
                <tr>
                    <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333"></td>
                    <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333" colspan="5"></td>
                </tr>

                {{-- ---------- --}}

                <?php $totalPotonganBiaya = 0; ?>
                @foreach ($potonganBiaya as $pbk => $pbv)
                    <tr>
                        <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                            @if ($pbk == 0)
                                <span class="font-bold">Potongan Biaya</span>
                            @endif
                        </td>
                        <td class="border-b px-1 text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray; max-width: 250px;">{{ $pbv->nama_pengurangan }}</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ $pbv->besar_pengurangan }}</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">1</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">0</td>
                        <td class="border-b px-1 text-right text-sm leading-none" style="padding: 3px, 3px; border-color: lightgray">{{ $pbv->besar_pengurangan }}</td>
                    </tr>

                    <?php $totalPotonganBiaya += $pbv->besar_pengurangan ?? 0; ?>
                @endforeach

                <tr>
                    <td class="px-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333; width: 150px;">
                        @if ($potonganBiaya->count() == 0)
                            <span class="font-bold">Potongan Biaya</span>
                        @endif
                    </td>
                    <td class="px-1 py-1 text-left text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey" colspan="4">
                        Total Potongan Biaya
                    </td>
                    <td class="px-1 py-1 text-right text-sm font-bold italic leading-none" style="padding: 3px, 3px; background-color: lightgrey">
                        Rp {{ number_format($totalPotonganBiaya, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- gap --}}
                <tr>
                    <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333"></td>
                    <td class="border-b px-1 py-1 text-sm font-bold leading-none" style="padding: 3px, 3px; border-color: #333" colspan="5"></td>
                </tr>

                {{-- ---------- --}}
                {{-- Total Keseluruhan --}}
                <tr>
                    <td class="border-b px-1 text-left font-bold italic leading-none" style="padding: 4px, 4px; background-color: lightblue; border-color: #333" colspan="5">Total Keseluruhan</td>
                    <td class="border-b px-1 text-right font-bold italic leading-none" style="padding: 4px, 4px; background-color: lightblue; border-color: #333">
                        Rp {{ number_format($totalRuang + $totalAllkategori + $totalResepPulang + $totalTambahanBiaya - $totalPotonganBiaya, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="my-4">
            <table class="w-full">
                <tr class="align-bottom">
                    <td class="w-full text-center">
                        <p class="mb-3 leading-none">
                            Mengetahui, <br>
                            a/n Direktur <br>
                            Kabid Umum dan Keuangan
                        </p>
                        @php
                            $HASHKoor = $asmenKeuangan?->sidikjari ? $asmenKeuangan?->sidikjari?->sidikjari : \Hash::make($asmenKeuangan?->nik);
                            $QRKoor = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $asmenKeuangan?->nama . '. ID : ' . $HASHKoor;
                        @endphp

                        <div class="relative inline-block h-28 w-28">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRKoor, 'QRCODE') }}" alt="barcode" class="h-28 w-28" />
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                        </div>

                        <p class="mt-3">{{ $asmenKeuangan?->nama }}</p>
                    </td>
                    <td class="w-full text-center">
                        <p class="mb-3">
                            &nbsp; <br>
                            Kasir
                        </p>
                        @php
                            $hash = $kasir?->sidikjari ? $kasir?->sidikjari?->sidikjari : \Hash::make($kasir?->nip);
                            $QRPetugas = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $kasir?->nama . '. ID : ' . $hash;
                        @endphp

                        <div class="relative inline-block h-28 w-28">
                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRPetugas, 'QRCODE') }}" alt="barcode" class="h-28 w-28" />
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
                        </div>

                        <p class="mt-3">{{ $kasir?->nama }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="mt-4">
            <p class="text-sm italic leading-none">NB : Mohon maaf apabila ada tagihan yang belum tertagihkan dalam perincian ini akan ditagihkan kemudian, dan apabila berlebih akan dikembalikan</p>
        </div>
    </main>
</x-print-layout>
