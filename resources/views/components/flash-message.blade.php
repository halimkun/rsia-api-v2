@if (session('success'))
    <div class="mx-auto -mb-5 mt-5 w-full sm:px-6 lg:px-8">
        <div class="mb-4 rounded-md bg-green-500 p-4 text-white">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mx-auto -mb-5 mt-5 w-full sm:px-6 lg:px-8">
        <div class="mb-4 rounded-md bg-red-500 p-4 text-white">
            {{ session('error') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mx-auto -mb-5 mt-5 w-full sm:px-6 lg:px-8">
        <div class="mb-4 rounded-md bg-red-500 p-4 text-white">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
