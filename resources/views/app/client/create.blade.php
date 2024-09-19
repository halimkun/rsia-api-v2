<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('OAuth Clients Edit') }}
        </h2>
    </x-slot>

    <div class="py-12 lg:py-6">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-row justify-between items-center">
                        <div class="text-xl font-bold">{{ __('Edit Client') }}</div>
                        <a href="{{ route('oauth.client.index') }}" class="bg-emerald-500 py-1 px-3 rounded-lg font-semibold text-white">Back</a>
                    </div>

                    <div class="py-5">
                        <div class="max-w-xl">
                            <form method="post" action="{{ route('oauth.client.store') }}" class="mt-6 space-y-6">
                                @csrf
                                <div>
                                    <x-label for="name" :value="__('Name')" />
                                    <x-input id="name" placeholder="My app client" name="name" type="text" class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
            
                                <div>
                                    <x-label for="redirect" :value="__('Redirect URL')" />
                                    <x-input id="redirect" placeholder="http://localhost:8000/callback" name="redirect" type="text" class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('redirect')" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-button>{{ __('Save') }}</x-button>
            
                                    @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
