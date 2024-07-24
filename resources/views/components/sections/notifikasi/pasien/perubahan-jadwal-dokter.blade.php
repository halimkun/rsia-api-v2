<div class="py-12 lg:py-6">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="flex gap-3">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200 bg-white p-4">
                    <form action="{{ route('notifikasi.pasien.jadwal-dokter.send') }}" method="post">
                        @csrf
                        {{-- card header with border bottom --}}
                        <div class="borber-b-2 border-b border-gray-200 pb-2">
                            <h2 class="font-semibold text-primary">Jadwal Baru Dokter</h2>
                        </div>

                        {{-- card body --}}
                        <div class="mt-5">
                            <div class="mb-4">
                                <label for="kd_dokter" class="mb-1 block text-base font-semibold">NIK Dokter <span class="text-sm font-normal text-gray-400">(Readonly)</span></label>
                                <input type="text" required name="kd_dokter" id="kd_dokter" class="input input-sm input-bordered w-full" readonly value="{{ request('kd_dokter') ? request('kd_dokter') : '-' }}">
                            </div>

                            <div class="mb-4">
                                <label for="kd_poli" class="mb-1 block text-base font-semibold">Poliklinik <span class="text-sm font-normal text-gray-400">(Readonly)</span></label>
                                <input type="text" required name="kd_poli" id="kd_poli" class="input input-sm input-bordered w-full" readonly value="{{ request('kd_poli') ? request('kd_poli') : '-' }}">
                            </div>

                            {{-- date input labeled tabggal --}}
                            <div class="mb-4">
                                <label for="tgl_registrasi" class="mb-1 block text-base font-semibold">Tanggal
                                    Registrasi <span class="text-sm font-normal text-gray-400">(Readonly)</span></label>
                                <input type="date" required name="tgl_registrasi" id="tgl_registrasi" class="input input-sm input-bordered w-full" readonly value="{{ request('tgl_registrasi') }}">
                            </div>

                            {{-- date input labeled tabggal --}}
                            <div class="mb-4">
                                <label for="tanggal" class="mb-1 block text-base font-semibold">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="input input-sm input-bordered w-full" required disabled>

                                <div class="mt-1">
                                    <label class="flex cursor-pointer select-none items-center">
                                        <input type="checkbox" name="tgl_sama" id="tgl_sama" class="checkbox checkbox-xs" checked>
                                        <span class="ml-2">Sama dengan Tanggal Registrasi</span>
                                    </label>
                                </div>
                            </div>

                            {{-- input time 2 times labeled jam mulai dan selesai --}}
                            <div class="flex gap-4">
                                <div class="mb-4 w-full">
                                    <label for="jam_mulai" class="mb-1 block text-base font-semibold">Jam Mulai</label>
                                    <input type="time" required name="jam_mulai" id="jam_mulai" class="input input-sm input-bordered w-full">
                                </div>

                                <div class="mb-4 w-full">
                                    <label for="jam_selesai" class="mb-1 block text-base font-semibold">Jam
                                        Selesai</label>
                                    <input type="time" name="jam_selesai" id="jam_selesai" class="input input-sm input-bordered w-full" required>

                                    {{-- checkbox tidak ditentukan --}}
                                    <div class="mt-1">
                                        <label class="flex cursor-pointer select-none items-center">
                                            <input type="checkbox" name="tidak_ditentukan" id="tidak_ditentukan" class="checkbox checkbox-xs">
                                            <span class="ml-2">Tidak Ditentukan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- alert --}}
                            <div role="alert" class="alert alert-warning mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-bold">Warning !</h3>
                                    <span>Notifikasi akan dikirim ke semua pasien yang terdaftar pada tabel.</span>
                                </div>
                            </div>

                            {{-- button notify --}}
                            <button class="btn btn-primary w-full">Notify</button>

                            {{-- show validation error --}}
                            @if ($errors->any())
                                <div class="alert alert-error mt-4">
                                    <div class="flex-1">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- table --}}
            <div class="flex w-full flex-col gap-3 sm:rounded-lg">
                <div class="w-full overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-4">
                        {{-- filter data --}}
                        <div class="borber-b-2 mb-4 border-b border-gray-200 pb-2">
                            <h2 class="font-semibold text-primary">Filter Pasien</h2>
                        </div>

                        <form method="GET" action="{{ route('notifikasi.pasien.jadwal-dokter') }}">
                            <div class="flex flex-col gap-4 lg:flex-row">
                                <div>
                                    <label for="kd_dokter" class="mb-1 block text-base font-semibold">Dokter</label>
                                    <select name="kd_dokter" id="kd_dokter" class="select select-bordered select-sm w-full" style="padding-top: 0px !important;" onchange="this.form.submit()">
                                        <option value="">--Pilih Dokter--</option>
                                        @foreach ($dokters as $dokter)
                                            <option value="{{ $dokter->kd_dokter }}" {{ request('kd_dokter') == $dokter->kd_dokter ? 'selected' : '' }}>
                                                {{ $dokter->dokter->nm_dokter }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="kd_poli" class="mb-1 block text-base font-semibold">Poliklinik</label>
                                    <select name="kd_poli" id="kd_poli" class="select select-bordered select-sm w-full" style="padding-top: 0px !important;" onchange="this.form.submit()">
                                        <option value="">--Pilih Poliklinik--</option>
                                        @foreach ($polikliniks as $poli)
                                            <option value="{{ $poli->kd_poli }}" {{ request('kd_poli') == $poli->kd_poli ? 'selected' : '' }}>
                                                {{ $poli->poliklinik->nm_poli }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- tanggal registrasi --}}
                                <div>
                                    <label for="tgl_registrasi" class="mb-1 block text-base font-semibold">Tgl
                                        Registrasi</label>
                                    <input type="date" name="tgl_registrasi" id="tgl_registrasi" class="input input-sm input-bordered w-full" value="{{ request('tgl_registrasi') }}" onchange="this.form.submit()">
                                </div>

                            </div>

                            <noscript>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </noscript>
                        </form>
                    </div>
                </div>

                <div class="w-full overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="bg-white p-6 shadow">
                        <div class="borber-b-2 mb-4 border-b border-gray-200 pb-2">
                            <h2 class="font-semibold text-primary">Data Pasien</h2>
                        </div>

                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. RM</th>
                                    <th>Tgl Registrasi</th>
                                    <th>Dokter</th>
                                    <th>Poliklinik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($registrasi->count() > 0)
                                    @foreach ($registrasi as $reg)
                                        <tr>
                                            <td>{{ $reg->pasienSomeData->nm_pasien }}</td>
                                            <td><span class="badge badge-primary badge-outline">{{ $reg->no_rkm_medis }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($reg->tgl_registrasi)->translatedFormat('l, d F Y') }}</td>
                                            <td>{{ $reg->dokter->nm_dokter }}</td>
                                            <td>{{ $reg->poliklinik->nm_poli }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="bg-gray-100/50 text-center">
                                        <td colspan="5" class="text-gray-400">
                                            <div class="py-6 font-semibold">
                                                Lakukan filter data terlebih dahulu untuk menampilkan data pasien.
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Pagination links -->
                        <div class="mt-4">
                            {{ $registrasi->appends(request()->query())->links() }}
                        </div>

                        <!-- Button below the table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // tidak_ditentukan checked disable jam_selesai on ready
    document.addEventListener('DOMContentLoaded', function() {
        const tidak_ditentukan = document.getElementById('tidak_ditentukan');
        const jam_selesai = document.getElementById('jam_selesai');

        tidak_ditentukan.addEventListener('change', function() {
            if (this.checked) {
                jam_selesai.disabled = true;
                jam_selesai.value = '';
            } else {
                jam_selesai.disabled = false;
            }
        });

        // jam sama 
        const tgl_sama = document.getElementById('tgl_sama');
        const tanggal = document.getElementById('tanggal');

        tgl_sama.addEventListener('change', function() {
            if (this.checked) {
                tanggal.disabled = true;
                tanggal.value = '';
            } else {
                tanggal.disabled = false;
            }
        });
    });
</script>
