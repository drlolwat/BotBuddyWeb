<x-layout>
    <div class="mb-2 text-xl font-bold">Proxy Management</div>

    <hr />

    <div class="grid grid-cols-[auto,1fr] gap-2">
        <div class="py-2 font-bold">Proxy Groups</div>
        <div class="py-2"><a href="{{ route('proxy.group.create') }}" class="btn btn-primary">Create Proxy Group</a></div>
    </div>
    <table class="table-auto">
        <thead>
        <tr>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth()->user()->proxy_groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr />

    <div class="grid grid-cols-[auto,1fr] gap-2">
        <div class="py-2 font-bold">Proxies</div>
        <div class="py-2"><a href="{{ route('proxy.create') }}" class="btn btn-primary">Add Proxy</a></div>
    </div>
    <table class="table-auto">
        <thead>
        <tr>
            <th>Host</th>
            <th>Port</th>
            <th>Password</th>
            <th>Group</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth()->user()->proxies as $proxy)
            <tr>
                <td>{{ $proxy->host }}</td>
                <td>{{ $proxy->port }}</td>
                <td>{{ $proxy->password }}</td>
                <td>{{ $proxy->proxy_group?->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-layout>
