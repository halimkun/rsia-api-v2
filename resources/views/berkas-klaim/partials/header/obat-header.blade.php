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

<div>
    <div>
        <table class="table w-full border-b" style="border-bottom: 1px solid #333;">
            @foreach ([
                'Nama Pasien' => $regPeriksa->pasien->nm_pasien,
                'No. RM'      => $regPeriksa->no_rkm_medis,
                'No. Rawat'   => $regPeriksa->no_rawat . "[{$regPeriksa->status_lanjut}]",
                'Pembiayaan'  => $regPeriksa->caraBayar->png_jawab,
            ] as $k => $v)
                <tr>
                    <td>{{ $k }}</td>
                    <td>:</td>
                    <td>{{ $v }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    
    <h4 class="text-center mb-3 mt-1 font-bold text-lg">
        DETAIL PEMBERIAN OBAT
    </h4>