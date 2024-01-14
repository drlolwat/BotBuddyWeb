<x-layout>
    <div class="mb-2 text-xl font-bold">Proxy Management</div>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">
        <div class="py-2 font-bold">Proxy Groups</div>
        <div class="py-2"><a href="{{ route('proxy.group.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</a></div>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach(auth()->user()->proxy_groups as $group)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $group->name }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('proxy.group.show', $group->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">
        <div class="py-2 font-bold">Proxies</div>
        <div class="py-2"><a href="{{ route('proxy.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Add</a></div>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Host</th>
                <th class="px-6 py-3">Port</th>
                <th class="px-6 py-3">Auth</th>
                <th class="px-6 py-3">Group</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach(auth()->user()->proxies as $proxy)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $proxy->host }}</td>
                    <td class="px-6 py-4">{{ $proxy->port }}</td>
                    <td class="px-6 py-4">{{ $proxy->username && $proxy->password ? $proxy->username.":".$proxy->password : "N/A" }}</td>
                    <td class="px-6 py-4">
                        @if($proxy->proxy_group)
                            <a href="{{ route('proxy.group.show', $proxy->proxy_group_id) }}" class="text-blue-600 hover:text-blue-500">{{ $proxy->proxy_group->name }}</a>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('proxy.show', $proxy->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</x-layout>
