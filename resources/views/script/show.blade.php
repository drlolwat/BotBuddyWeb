<x-layout>
    <div class="mb-2 text-xl font-bold">Update Script</div>
    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    <form method="post" action="{{ route('script.update', $script->id) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div>Name</div>
        <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="{{ $script->name }}" />
        <div>Script</div>
        <input type="text" name="script" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="{{ $script->script }}" />
        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Update</button>
            <button form="delete" type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Delete</button>
        </div>
    </form>
    <form id="delete" method="post" action="{{ route('script.destroy', $script->id) }}">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
</x-layout>
