<x-berkas-klaim._header-with-identity :regPeriksa="$regPeriksa" title="HASIL PEMERIKSAAN USG"/>

<main>
    <table class="table w-full border" style="border-color: #333;">
        <tr>
            <td colspan="3" class="px-2 py-1">
                <p class="leading-5 text-sm">
                    {!! nl2br($usg->catatan) !!}  
                </p>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="text-center" style="width: 350px;">
                <img src="{{ $barcodeDPJP }}" alt="barcode DPJP" style="width: 150px; height: 150px;"/>
                <div class="mt-2">{{ $dpjp->nm_dokter }}</div>
            </td>
        </tr>
    </table>
</main>