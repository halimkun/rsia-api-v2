<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header style="width: 100%;">
        <table style="border-bottom: 1px solid #333; width: 100%">
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

    <main>
        <div style="width: 100%;">
            <table style="width: 100%; border-bottom: 1px solid #333;">
                @foreach ([
                    'Nama Pasien' => $regPeriksa->pasien->nm_pasien,
                    'No. RM' => $regPeriksa->no_rkm_medis,
                    'No. Rawat' => $regPeriksa->no_rawat . "[{$regPeriksa->status_lanjut}]",
                    'Pembiayaan' => $regPeriksa->caraBayar->png_jawab,
                ] as $k => $v)
                    <tr>
                        <td>{{ $k }}</td>
                        <td>:</td>
                        <td>{{ $v }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        
        <h4 style="text-align: center;">
            DETAIL PEMBERIAN OBAT
        </h4>
