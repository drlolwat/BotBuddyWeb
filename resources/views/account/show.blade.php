<x-layout>
    <div class="mb-2 text-xl font-bold">Update Account</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('account.update', $account->id) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div>Email</div>
        <input type="text" name="email" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="{{ $account->email }}" />
        <div>Password</div>
        <input type="text" name="password" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="{{ $account->password }}" />
        <div>Group</div>
        <select name="account_group_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->account_groups as $group)
                <option value="{{ $group->id }}" @if($account->account_group_id == $group->id) selected @endif >{{ $group->name }}</option>
            @endforeach
        </select>
        <div>Proxy</div>
        <select name="proxy_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->proxies as $proxy)
                <option value="{{ $proxy->id }}" @if($account->proxy_id == $proxy->id) selected @endif >{{ $proxy->host }}:{{ $proxy->port }}</option>
            @endforeach
        </select>
        <div>Script</div>
        <select name="script_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            @foreach(auth()->user()->scripts as $script)
                <option value="{{ $script->id }}" @if($account->script_id == $script->id) selected @endif >{{ $script->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Update</button>
            <button form="delete" type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Delete</button>
        </div>
    </form>
    <form id="delete" method="post" action="{{ route('account.destroy', $account->id) }}">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
</x-layout>
