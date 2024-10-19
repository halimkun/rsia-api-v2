<x-print-layout>
	@push('styles')
        <style>
            @page {
                /* meaning top, right, bottom, left */
                margin: 155px 50px 50px 50px;
            }

            @page :first {
                margin-top: 50px; /* Custom margin for the first page */
            }

            header { position: fixed; top: -10px; left: 0px; right: 0px; height: 50px; max-height: min-content !important; }
            footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        </style>
    @endpush

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

    <main style="margin-top: 90px;">
		<div class="text-center">
			<h4 class="text-lg font-bold">HASIL PEMERIKSAAN RADIOLOGI</h4>
		</div>
	
		<div class="mt-3">
			<table class="table w-full">
				<tr class="align-top">
					<td>
						<table class="table w-full">
							@foreach ([
								'No. RM'      => $regPeriksa?->no_rkm_medis,
								'Nama Pasien' => $regPeriksa?->pasien?->nm_pasien,
								'JK / Umur'   => $regPeriksa?->pasien?->jk . ' / ' . $regPeriksa?->umurdaftar . ' ' . $regPeriksa?->sttsumur,
								'Alamat'      => $regPeriksa?->pasien?->alamat,
								'No. Periksa' => $radiologi?->no_rawat,
							] as $key => $val)
								<tr class="align-top">
									<td class="text-nowrap whitespace-nowrap leading-5">{{ $key }}</td>
									<td class="px-2 leading-5">:</td>
									<td class="w-full leading-5">{{ $val }}</td>
								</tr>
							@endforeach
						</table>
					</td>
					<td>
						<table class="table w-full">
							@foreach ([
								'Penanggung Jawab' => $radiologi?->dokter?->nm_dokter,
								'Dokter Pengirim'  => $radiologi?->dokterPerujuk?->nm_dokter,
								'Tgl. Pemeriksaan' => $radiologi?->tgl_periksa,
								'Jam pemeriksaan'  => $radiologi?->jam,
                                'Jenis Perawan'    => $radiologi?->jenisPerawatan?->nm_perawatan,
							] as $key => $val)
								<tr class="align-top">
									<td class="text-nowrap whitespace-nowrap leading-5">{{ $key }}</td>
									<td class="px-2 leading-5">:</td>
									<td class="w-full leading-5">{{ $val }}</td>
								</tr>
							@endforeach
						</table>
					</td>
				</tr>
			</table>
		</div>

		<div class="mt-5 w-full">
			<div class="w-full border-b" style="border-bottom: 1px solid #333;">
				<div class="border-t border-b mb-2" style="border-top: 1px solid #333; border-bottom: 1px solid #333;">
					<h4 class="text-base font-bold">Hasil Pemeriksaan :</h4>
				</div>
				<p class="leading-none pb-2">{!! nl2br($radiologi?->hasilRadiologi?->hasil) !!}</p>
			</div>
		</div>

		@php
			$QRDokter = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $radiologi?->dokter?->nm_dokter . '. ID : ' . \Hash::make($radiologi?->dokter?->kd_dokter);
			$QRPetugas = 'Dikeluarkan di RSIA Aisyiyah Pekajangan, Ditandatangani secara elektronik oleh ' . $radiologi?->petugas?->nama . '. ID : ' . $radiologi?->petugas?->sidikjari?->sidikjari;
		@endphp
	
		<div class="mt-5">
			<table class="table w-full">
				<tr>
					<td class="text-center">
                        <div>&nbsp;</div>
						<div class="mb-2">Penanggung Jawab</div>
						<div class="relative inline-block h-28 w-28">
							<img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRDokter, 'QRCODE') }}" alt="barcode" class="h-2w-28 w-28" />
							<img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
						</div>
						<div class="mt-2">{{ $radiologi?->dokter?->nm_dokter }}</div>
					</td>
					<td class="text-center">
						<div class="text-base leading-none">Tgl. Cetak : {{ date('d/m/Y H:i:s', strtotime($radiologi?->tgl_periksa . ' ' . $radiologi?->jam)) }}</div>
						<div class="mb-2">Petugas Laboratorium</div>
						<div class="relative inline-block h-28 w-28">
							<img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($QRPetugas, 'QRCODE') }}" alt="barcode" class="h-2w-28 w-28" />
							<img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="h-8 w-8" style="position: absolute !important; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;" />
						</div>
						<div class="mt-2">{{ $radiologi?->petugas?->nama }}</div>
					</td>
				</tr>
			</table>
		</div>
	</main>
</x-print-layout>