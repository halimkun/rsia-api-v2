@props([
    'regPeriksa',
    'title',
])

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
                                    <td class="border text-center align-middle font-bold leading-none py-1" style="border-color: #333;">
                                        {{ $title }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border text-center" style="width: 70%; border-color: #333;">
                @if ($regPeriksa)
                <table class="table w-full table-auto">
                    @foreach ([
                        'No. RM'     => \App\Helpers\SafeAccess::object($regPeriksa, 'no_rkm_medis', '-'),
                        'Nama'       => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->nm_pasien', '-'),
                        'Umur'       => \App\Helpers\SafeAccess::object($regPeriksa, 'umurdaftar', '-') . ' ' . \App\Helpers\SafeAccess::object($regPeriksa, 'sttsumur', '-'),
                        'Tgl. Lahir' => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->tgl_lahir', '-'),
                        'Alamat'     => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->alamat', '-'),
                        'No. HP'     => \App\Helpers\SafeAccess::object($regPeriksa, 'pasien->no_tlp', '-'),
                    ] as $key => $value)
                        <tr>
                            <td class="text-nowrap whitespace-nowrap px-1 text-left text-sm leading-4">{{ $key }}</td>
                            <td class="px-1 text-left text-sm leading-4" style="width: 1px;">:</td>
                            <td class="w-full px-1 text-left text-sm leading-4">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
                @endif
            </td>
        </tr>
    </table>
</header>