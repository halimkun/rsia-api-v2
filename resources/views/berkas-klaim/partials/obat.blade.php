<table class="table w-full border-collapse" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #333; border-top: 1px solid #333; padding:8px; ">No.</th>
            <th style="border-bottom: 1px solid #333; border-top: 1px solid #333; padding:8px; text-align: left">Obat</th>
            <th style="border-bottom: 1px solid #333; border-top: 1px solid #333; padding:8px; ">Qty</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($obat as $key => $item)
            <tr>
                <th colspan="3" style="border-bottom: 1px solid #333; text-align: left; padding-top: 15px;">{{ $key }}</th>
            </tr>

            @foreach ($item as $sk => $sv)
                <tr>
                    <td style="text-align: right; padding-right: 8px;">{{ $loop->iteration }}.</td>
                    <td style="text-align: left;">{{ $sv->obat->nama_brng }}</td>
                    <td style="text-align: center;">{{ $sv->jml }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>