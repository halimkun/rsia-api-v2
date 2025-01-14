    <table class="table-auto border-collapse border border-gray-300 w-full text-sm text-left">
        <tbody>
            @php
                $counter = 1; // Inisialisasi counter untuk penomoran global
            @endphp

            @foreach ($obat as $key => $item)
                <tr class="bg-gray-200">
                    <th class="text-left border border-gray-300 px-4 py-1" style="background-color: #f1f1f1" colspan="2">
                        {{-- key is date time, convert it using carbon --}}
                        {{ \Carbon\Carbon::parse($key)->translatedFormat('l, d F Y H:i') }}
                    </th>
                </tr>

                <tr class="bg-gray-200">
                    <th class="px-4 py-1" style="background-color: #f1f1f1">
                        <table class="w-full table-auto">
                            <tbody>
                                <tr class="align-top">
                                    <td class="text-left">
                                        Nama Obat
                                    </td>
                                    <td class="text-right">
                                        Qty
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </th>
                    <th class="px-4 py-1" style="background-color: #f1f1f1">
                        <table class="w-full table-auto">
                            <tbody>
                                <tr class="align-top">
                                    <td class="text-left">
                                        Nama Obat
                                    </td>
                                    <td class="text-right">
                                        Qty
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </th>
                </tr>

                @php
                    $chunks = $item->chunk(2); // Membagi item menjadi grup dengan maksimal 3 item
                @endphp

                @foreach ($chunks as $chunk)
                    <tr>
                        @foreach ($chunk as $sk => $sv)
                            <td class="border border-gray-300 px-4 py-1">
                                
                                <table class="w-full table-auto">
                                    <tbody>
                                        <tr class="align-top">
                                            <td class="text-left">
                                                <p>{{ $sv->obat->nama_brng }}</p>
                                            </td>
                                            <td class="text-right">
                                                {{ $sv->jml }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                            @php
                                $counter++;
                            @endphp
                        @endforeach

                        @if ($chunk->count() < 2)
                            @for ($i = 0; $i < 2 - $chunk->count(); $i++)
                                <td class="border border-gray-300 px-4 py-1"></td>
                            @endfor
                        @endif
                    </tr>
                @endforeach

                @php
                    $counter = 1;
                @endphp
            @endforeach
        </tbody>
    </table>