<x-layout>
    <div class="mb-2 text-xl font-bold">Update Account Group</div>

    @if($errors->isNotEmpty())
        <div>{{ $errors }}</div>
    @endif
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form method="post" action="{{ route('account.group.update', $group->id) }}">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <div>Name</div>
        <input type="text" name="name" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full" value="{{ $group->name }}" />
        <div class="grid grid-cols-2 gap-2">
            <button type="submit" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">Update</button>
            <button form="delete" type="submit" class="border-2 border-gray-300 rounded-lg p-2 mb-2 w-full">Delete</button>
        </div>
    </form>
    <form id="delete" method="post" action="{{ route('account.group.destroy', $group->id) }}">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
</x-layout>
