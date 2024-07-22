<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi Pasien') }}
            </h2>

            <div class="flex gap-3">
                <x-nav-link :href="route('notifikasi.pasien', ['action' => 'default'])" :active="request('action') == 'default'">
                    {{ __('Default') }}
                </x-nav-link>

                <x-nav-link :href="route('notifikasi.pasien', ['action' => 'perubahan-jadwal-dokter'])" :active="request('action') == 'perubahan-jadwal-dokter'">
                    {{ __('Perubahan Jadwal Dokter') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    @if (request()->has('action') && request('action') == 'default')
        <x-sections.notifikasi.pasien.default />
    @endif
    
    @if (request()->has('action') && request('action') == 'perubahan-jadwal-dokter')
        <x-sections.notifikasi.pasien.perubahan-jadwal-dokter />
    @endif

</x-app-layout>