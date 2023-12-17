<x-layout>
    <div class="mb-2 text-xl font-bold">Account Management</div>
    <hr />

    <div class="grid grid-cols-[auto,1fr] gap-2">
        <div class="py-2 font-bold">Account Groups</div>
        <div class="py-2"><a href="{{ route('account.group.create') }}" class="btn btn-primary">Create Account Group</a></div>
    </div>
    <table class="table-auto">
        <thead>
        <tr>
            <th>Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth()->user()->account_groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr />

    <div class="grid grid-cols-[auto,1fr] gap-2">
        <div class="py-2 font-bold">Accounts</div>
        <div class="py-2"><a href="{{ route('account.create') }}" class="btn btn-primary">Create Account</a></div>
    </div>
    <table class="table-auto">
        <thead>
        <tr>
            <th>Email</th>
            <th>Password</th>
            <th>Group</th>
            <th>Proxy</th>
            <th>Script</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth()->user()->accounts as $account)
            <tr>
                <td>{{ $account->email }}</td>
                <td>{{ $account->password }}</td>
                <td>{{ $account->account_group?->name }}</td>
                <td>@if($account->proxy){{ $account->proxy->host }}:{{ $account->proxy->port }}@endif</td>
                <td>{{ $account->script->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layout>
