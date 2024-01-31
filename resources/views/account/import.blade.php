<x-layout>
    <div class="mb-2 text-xl font-bold">Import Accounts</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('account.import.store') }}" enctype="multipart/form-data">
        @csrf
        <div>Accounts</div>
        <textarea name="account_textarea" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full"></textarea>
        <details class="mb-2" open>
            <summary>Accepted formats</summary>
            <ul class="text-xs mb-2 w-full">
                <li>user:pass</li>
                <li>user:pass:2fa_pass</li>
                <li>user:pass:proxy_host:proxy_user:proxy_pass</li>
                <li>user:pass:2fa_pass:proxy_host:proxy_user:proxy_pass</li>
            </ul>
        </details>
        <div>Accounts from file</div>
        <input type="file" name="account_file" class=" mb-2 w-full" />
        <div>Group</div>
        <select name="account_group_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->account_groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
        <div>Agent</div>
        <select name="agent_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->agents as $agent)
                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
            @endforeach
        </select>
        <div>Proxy (used for accounts without a proxy)</div>
        <select name="proxy_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->proxies as $proxy)
                <option value="{{ $proxy->id }}">{{ $proxy->host }}:{{ $proxy->port }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Import</button>
    </form>
</x-layout>
