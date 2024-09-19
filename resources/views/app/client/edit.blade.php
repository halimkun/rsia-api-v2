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
                            <form action="{{ route('oauth.client.update', $client->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ $client->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 sm:text-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="redirect" class="block text-sm font-medium text-gray-700">Redirect</label>
                                    <input type="text" name="redirect" id="redirect" value="{{ $client->redirect }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 sm:text-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="secret" class="block text-sm font-medium text-gray-700">Secret</label>
                                    <input type="text" name="secret" id="secret" value="{{ $client->secret }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 sm:text-sm" disabled>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" class="bg-blue-500 py-1 px-3 rounded-lg font-semibold text-white">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
