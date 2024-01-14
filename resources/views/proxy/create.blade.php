<x-layout>
    <div class="mb-2 text-xl font-bold">Add Proxy</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('proxy.store') }}">
        @csrf
        <div>Host</div>
        <input type="text" name="host" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>Port</div>
        <input type="text" name="port" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>Username (Optional)</div>
        <input type="text" name="username" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>Password (Optional)</div>
        <input type="text" name="password" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>Group</div>
        <select name="proxy_group_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            <option value="0">None</option>
            @foreach(auth()->user()->proxy_groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Add</button>
    </form>
</x-layout>
