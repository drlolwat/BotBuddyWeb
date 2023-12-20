<x-layout>
    <div class="mb-2 text-xl font-bold">Script Management</div>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">
        <div class="py-2 font-bold">Scripts</div>
        <div class="py-2"><a href="{{ route('script.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Add</a></div>
    </div>


    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Script</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach(auth()->user()->scripts as $script)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $script->name }}</td>
                    <td class="px-6 py-4">{{ $script->script }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('script.show', $script->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</x-layout>
