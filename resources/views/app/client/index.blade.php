<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('OAuth Data') }}
            </h2>

            <div class="flex gap-3">
                <x-nav-link :href="route('oauth.client.index')" :active="request()->routeIs('oauth.client.index')">
                    {{ __('Clients') }}
                </x-nav-link>

                <x-nav-link :href="route('oauth.token.index')" :active="request()->routeIs('oauth.token.index')">
                    {{ __('Tokens') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12 lg:py-6">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-row justify-between items-center">
                        <div class="text-xl font-bold">{{ __('Client List') }}</div>
                        <a href="{{ route('oauth.client.create') }}" class="bg-emerald-500 py-1 px-3 rounded-lg text-white">Add Client</a>
                    </div>

                    <div class="py-5">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Client ID</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Name</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Redirect</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Secrete</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td class="border-b border-gray-300 py-2">{{ $client->id }}</td>
                                        <td class="border-b border-gray-300 py-2">{{ $client->name }}</td>
                                        <td class="border-b border-gray-300 py-2">{{ $client->redirect }}</td>
                                        <td class="border-b border-gray-300 py-2">
                                            <code class="bg-gray-200 px-2 py-1 rounded-lg">{{ $client->secret }}</code>
                                        </td>
                                        <td class="border-b border-gray-300 py-2">
                                            <a href="{{ route('oauth.client.edit', $client->id) }}" class="bg-indigo-600 py-1 px-3 rounded-lg text-white">Edit</a>
                                            <form action="{{ route('oauth.client.destroy', $client->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 py-1 px-3 rounded-lg text-white" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
