<x-layout>
    <div class="mb-2 text-xl font-bold">Create Account Group</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('account.group.store') }}">
        @csrf
        <div>Name</div>
        <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>Script</div>
        <select name="script_id" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">
            @foreach(auth()->user()->scripts as $script)
                <option value="{{ $script->id }}">{{ $script->name }}</option>
            @endforeach
        </select>
        <div>Script Parameters</div>
        <input type="text" name="script_params" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" />
        <div>World (f2p, members, or world ID)</div>
        <input type="text" name="world" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="f2p" />
        <div>FPS</div>
        <input type="number" name="fps" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="5"  />
        <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Create</button>
    </form>
</x-layout>
