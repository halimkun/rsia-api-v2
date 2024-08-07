<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi Pasien') }}
            </h2>

            <div class="flex gap-3">
                <x-nav-link :href="route('notifikasi.pasien')" :active="request()->routeIs('notifikasi.pasien')">
                    {{ __('Default') }}
                </x-nav-link>

                <x-nav-link :href="route('notifikasi.pasien.jadwal-dokter')" :active="request()->routeIs('notifikasi.pasien.jadwal-dokter')">
                    {{ __('Perubahan Jadwal Dokter') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    <x-sections.notifikasi.pasien.default />
    
    @if (request()->has('action') && request('action') == 'perubahan-jadwal-dokter')
        <x-sections.notifikasi.pasien.perubahan-jadwal-dokter :dokters="$dokters" :polikliniks="$polikliniks" :registrasi="$registrasi" />
    @endif
</x-app-layout>