<x-layout>
    <div class="mb-2 text-xl font-bold">Account Management</div>
    @if($errors->isNotEmpty())
        <div class="bg-red-500 p-2 text-white">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if (session('status'))
        <div class="bg-green-500 p-2 text-white">{{ session('status') }}</div>
    @endif

    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">
        <div class="py-2 font-bold">Account Groups</div>
        <div class="py-2"><a href="{{ route('account.group.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</a></div>
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
            @foreach(auth()->user()->account_groups as $group)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $group->name }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('account.group.show', $group->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-[1fr,auto] gap-2 py-2">
        <div class="py-2 font-bold">Accounts</div>
        <div class="py-2 grid grid-cols-2 gap-2">
            <a href="{{ route('account.create') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</a>
            <a href="{{ route('account.import') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Import</a>
        </div>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white bg-gray-500">
            <tr>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Password</th>
                <th class="px-6 py-3">Group</th>
                <th class="px-6 py-3">Agent</th>
                <th class="px-6 py-3">Proxy</th>
                <th class="px-6 py-3">Script</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $account)
                <tr class="bg-white border hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $account->email }}</td>
                    <td class="px-6 py-4">{{ $account->password }}</td>
                    <td class="px-6 py-4">{{ $account->account_group?->name }}</td>
                    <td class="px-6 py-4">{{ $account->agent?->name }}</td>
                    <td class="px-6 py-4">@if($account->proxy){{ $account->proxy->host }}:{{ $account->proxy->port }}@endif</td>
                    <td class="px-6 py-4">@if($account->script?->name) {{ $account->script->name }} @else {{ $account->account_group?->script->name }} @endif</td>
                    @if($account->status == 'Banned')
                        @if($account->perm_banned_at)
                            <td class="px-6 py-4 text-[red]">Banned&nbsp;(Permanent)</td>
                        @elseif($account->temp_banned_at)
                            <td class="px-6 py-4 text-[red]">Banned&nbsp;(Temporary)</td>
                        @else
                            <td class="px-6 py-4 text-[red]">Banned</td>
                        @endif
                    @else
                        <td class="px-6 py-4">{{ $account->status }}</td>
                    @endif
                    <td class="px-6 py-4 gap-2 flex">
                        @if($account->status == 'Stopped' || $account->status == 'Stopping' || $account->status == 'Banned')
                            <form method="post" action="{{ route('account.start', $account->id) }}">
                                @csrf
                                <button type="submit" class="font-medium text-blue-600 hover:underline">Start</button>
                            </form>
                        @elseif($account->status == 'Running' || $account->status == 'Starting')
                            <form method="post" action="{{ route('account.stop', $account->id) }}">
                                @csrf
                                <button type="submit" class="font-medium text-blue-600 hover:underline">Stop</button>
                            </form>
                        @endif
                        <a href="{{ route('account.show', $account->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</x-layout>
