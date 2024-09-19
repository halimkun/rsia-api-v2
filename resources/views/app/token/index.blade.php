<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Token Data') }}
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
                        <div class="text-xl font-bold">{{ __('Token List') }}</div>

                        {{-- button delete expired & button delete revoked --}}
                        <div class="flex gap-3">
                            <form action="{{ route('oauth.token.delete.expired') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-200 text-red-600 py-1 px-3 text-sm font-bold rounded-lg" onclick="return confirm('Are you sure?')">Delete Expired</button>
                            </form>

                            <form action="{{ route('oauth.token.delete.revoked') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-200 text-red-600 py-1 px-3 text-sm font-bold rounded-lg" onclick="return confirm('Are you sure?')">Delete Revoked</button>
                            </form>
                        </div>
                    </div>

                    <div class="py-5">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">ID</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">User ID</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Client ID</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Scopes</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Revoked</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Created At</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Updated At</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">Expires At</th>
                                    <th class="border-b-2 border-gray-300 py-2 font-bold">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tokens as $token)
                                    <tr>
                                        <td class="border-b border-gray-300 py-2">
                                            <code class="bg-gray-200 px-2 py-1 rounded-lg max-w-xs truncate overflow-ellipsis inline-block">
                                                {{ \Illuminate\Support\Str::limit($token->id, 38) }}
                                            </code>
                                        </td>
                                        <td class="border-b border-gray-300 py-2">{{ $token->user_id }}</td>
                                        <td class="border-b border-gray-300 py-2">{{ $token->client_id }}</td>
                                        <td class="border-b border-gray-300 py-2">
                                            @if (empty($token->scopes))
                                                <code class="bg-gray-200 px-2 py-1 rounded-lg">-</code>
                                            @else
                                                @foreach($token->scopes as $scope)
                                                    <code class="bg-gray-200 px-2 py-1 rounded-lg">{{ $scope }}</code>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="border-b border-gray-300 py-2">
                                            @if ($token->revoked)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-red-200 font-semibold text-red-800">Yes</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs bg-green-200 font-semibold text-green-800">No</span>
                                            @endif
                                        </td>
                                        <td class="border-b border-gray-300 py-2">{{ $token->created_at }}</td>
                                        <td class="border-b border-gray-300 py-2">{{ $token->updated_at }}</td>
                                        <td class="border-b border-gray-300 py-2">{{ $token->expires_at }}</td>
                                        <td class="border-b border-gray-300 py-2">
                                            <form action="{{ route('oauth.token.revoke', $token->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 py-1 px-3 rounded-lg text-white" onclick="return confirm('Are you sure?')">Revoke</button>
                                            </form>
                                            
                                            <form action="{{ route('oauth.token.destroy', $token->id) }}" method="POST" class="inline">
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
