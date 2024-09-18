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
            <table class="table w-full border-b" style="border-bottom: 1px solid #000;">
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

    <main style="margin-top: 90px">
        <div class="text-center mb-3 text-lg font-bold">
            DETAIL PEMBERIAN OBAT
        </div>

        <div class="pb-4 mb-3">
            <table class="table w-full mb-3">
                @foreach ([
                    "Nama Pasien" => $regPeriksa->pasien->nm_pasien,
                    "No. RM"      => $regPeriksa->no_rkm_medis,
                    "No. Rawat"   => $regPeriksa->no_rawat . "[{$regPeriksa->status_lanjut}]",
                    "Pembiayaan"  => $regPeriksa->caraBayar->png_jawab,
                ] as $k => $v)
                    <tr>
                        <td class="leading-none whitespace-nowrap text-nowrap" style="width: 80px;">{{ $k }}</td>
                        <td class="leading-none px-2" style="width: 5px">:</td>
                        <td class="leading-none ">{{ $v }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <table class="table w-full">
            <thead>
                <tr class="border-b border-t align-middle" style="background-color: lightcyan; border-top: 0.5px solid #333; border-bottom: 0.5px solid #333;">
                    <th class="py-1 leading-none text-center" style="width: 60px">No.</th>
                    <th class="py-1 leading-none text-left">Obat</th>
                    <th class="py-1 leading-none text-center" style="width: 80px">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($obat as $key => $item)
                    <tr class="border-b align-middle" style="border-bottom-color: #313131;">
                        <td colspan="3" class="py-1 font-bold leading-none">{{ $key }}</td>
                    </tr>

                    @foreach ($item as $sk => $sv)
                    <tr class="border-b align-middle" style="border-bottom-color: #a5a5a5;">
                        <td class="text-center"><span>{{ $loop->iteration }}</span></td>
                        <td class="whitespace-nowrap text-nowrap">{{ $sv->obat->nama_brng }}</td>
                        <td class="text-center">{{ $sv->jml }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </main>
</x-print-layout>